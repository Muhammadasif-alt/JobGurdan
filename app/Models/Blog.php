<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'blog_catgories_id',
        'author_id',
        'job_id',
        'author_name',
        'title',
        'slug',
        'excerpt',
        'content',
        'tags',
        'featured_image',
        'gallery_images',
        'meta_title',
        'meta_description',
        'reading_time',
        'status',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCatgories::class, 'blog_catgories_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Words that say nothing about what a guide is about, so they never
     * count towards how related two guides are.
     *
     * @var list<string>
     */
    private const RELATED_STOPWORDS = [
        'the', 'and', 'for', 'with', 'from', 'how', 'get', 'can', 'does', 'much', 'make', 'earn',
        'job', 'jobs', 'work', 'worker', 'workers', 'guide', 'guides', 'visa', 'sponsorship',
        'salary', 'pay', 'pakistan', 'pakistani', 'pkr', 'foreigner', 'foreigners', 'foreign',
        'scams', 'scam', 'avoid', 'rules', '2025', '2026', 'new', 'best', 'top', 'your',
    ];

    /**
     * Country words: two guides about the same country are worth more to a
     * reader than two guides that merely share an occupation.
     *
     * @var list<string>
     */
    private const RELATED_COUNTRY_WORDS = ['usa', 'us', 'uk', 'canada', 'uae', 'germany', 'australia', 'dubai'];

    /**
     * Published guides most relevant to this one: shared tags and shared
     * topic words, with a bonus for the same country. Guides of the same
     * category fill any remaining places, newest first.
     *
     * @return \Illuminate\Support\Collection<int, Blog>
     */
    public function relatedPosts(int $limit = 5): \Illuminate\Support\Collection
    {
        $ownTags = $this->tagList();
        $ownWords = $this->topicWords();

        $scored = static::query()
            ->where('status', 'published')
            ->where('id', '!=', $this->id)
            ->get(['id', 'title', 'tags', 'blog_catgories_id', 'published_at'])
            ->map(function (Blog $candidate) use ($ownTags, $ownWords): array {
                $sharedTags = count(array_intersect($ownTags, $candidate->tagList()));
                $sharedWords = array_intersect($ownWords, $candidate->topicWords());
                $sharedCountry = count(array_intersect($sharedWords, self::RELATED_COUNTRY_WORDS));

                return [
                    'id' => $candidate->id,
                    'score' => ($sharedTags * 4) + count($sharedWords) + ($sharedCountry * 2)
                        + ($candidate->blog_catgories_id === $this->blog_catgories_id ? 1 : 0),
                    'at' => $candidate->published_at?->getTimestamp() ?? 0,
                ];
            })
            ->filter(fn (array $row): bool => $row['score'] > 1)
            ->sort(fn (array $a, array $b): int => [$b['score'], $b['at']] <=> [$a['score'], $a['at']])
            ->take($limit)
            ->pluck('id');

        $posts = static::query()->whereIn('id', $scored)->get()->sortBy(fn (Blog $post) => $scored->search($post->id))->values();

        if ($posts->count() < $limit) {
            $filler = static::query()
                ->where('status', 'published')
                ->where('id', '!=', $this->id)
                ->whereNotIn('id', $posts->pluck('id'))
                ->when($this->blog_catgories_id, fn ($query) => $query->where('blog_catgories_id', $this->blog_catgories_id))
                ->latest('published_at')
                ->take($limit - $posts->count())
                ->get();

            $posts = $posts->concat($filler)->values();
        }

        return $posts;
    }

    /**
     * @return list<string>
     */
    public function tagList(): array
    {
        return array_values(array_filter(array_map(
            fn (string $tag): string => strtolower(trim($tag)),
            explode(',', (string) $this->tags)
        )));
    }

    /**
     * @return list<string>
     */
    private function topicWords(): array
    {
        $words = preg_split('/[^a-z0-9]+/', strtolower(implode(' ', $this->tagList()).' '.$this->title)) ?: [];

        return array_values(array_unique(array_filter(
            $words,
            fn (string $word): bool => strlen($word) >= 2 && ! in_array($word, self::RELATED_STOPWORDS, true)
        )));
    }

    /** The vacancy a job-spotlight post writes about, if it has one. */
    public function job(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
