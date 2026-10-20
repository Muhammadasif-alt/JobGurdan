<?php

namespace App\Console\Commands;

use App\Models\Blog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Read-only audit of the blog. Writes a CSV so a person can review every post
 * and decide; nothing is changed or deleted.
 */
class AuditBlogPosts extends Command
{
    protected $signature = 'blogs:audit
        {--path= : Where to write the CSV (default storage/app/blog-audit.csv)}
        {--min-words=800 : Posts shorter than this are flagged}
        {--similarity=0.75 : Title overlap (0 to 1) above which two posts count as near duplicates}';

    protected $description = 'Export every blog post with word count, source links and a suggested action (keep, merge, noindex). Changes nothing.';

    private const STOPWORDS = [
        'the', 'and', 'for', 'with', 'from', 'how', 'to', 'in', 'of', 'a', 'an', 'on', 'at', 'is', 'are', 'your', 'you',
        'what', 'who', 'do', 'does', 'can', 'get', 'guide', 'complete', 'full', 'new', 'best', 'top', 'step', 'by',
        '2024', '2025', '2026', '2027',
    ];

    public function handle(): int
    {
        $minWords = (int) $this->option('min-words');
        $threshold = (float) $this->option('similarity');
        $path = $this->option('path') ?: storage_path('app/blog-audit.csv');

        $rows = [];
        Blog::query()->with('category:id,name')->orderBy('id')->chunk(100, function ($posts) use (&$rows) {
            foreach ($posts as $post) {
                $rows[] = $this->describe($post);
            }
        });

        $this->markNearDuplicates($rows, $threshold);

        foreach ($rows as &$row) {
            $row['flags'] = $this->flags($row, $minWords);
            $row['suggested_action'] = $this->action($row);
            unset($row['tokens'], $row['strength']);
        }
        unset($row);

        File::ensureDirectoryExists(dirname($path));
        $handle = fopen($path, 'w');
        fputcsv($handle, ['id', 'title', 'slug', 'category', 'status', 'published_at', 'word_count', 'source_links', 'has_sources_section', 'noindex', 'flags', 'suggested_action']);
        foreach ($rows as $row) {
            fputcsv($handle, [
                $row['id'], $row['title'], $row['slug'], $row['category'], $row['status'], $row['published_at'],
                $row['word_count'], $row['source_links'], $row['has_sources'] ? 'yes' : 'no', $row['noindex'] ? 'yes' : 'no',
                $row['flags'], $row['suggested_action'],
            ]);
        }
        fclose($handle);

        $counts = collect($rows)->countBy('suggested_action');
        $this->info('Wrote '.count($rows)." posts to {$path}");
        $this->table(['suggested_action', 'posts'], $counts->map(fn ($n, $action) => [$action ?: '-', $n])->values()->all());
        $this->line('Short posts: '.collect($rows)->filter(fn ($r) => str_contains($r['flags'], 'short'))->count()
            .', no source links: '.collect($rows)->filter(fn ($r) => str_contains($r['flags'], 'no_sources'))->count()
            .', near duplicates: '.collect($rows)->filter(fn ($r) => str_contains($r['flags'], 'near_duplicate'))->count());

        return self::SUCCESS;
    }

    /**
     * @return array<string, mixed>
     */
    private function describe(Blog $post): array
    {
        $content = (string) $post->content;
        $text = trim(html_entity_decode(strip_tags($content)));
        $wordCount = str_word_count($text) ?: count(preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY));

        preg_match_all('#<a\s[^>]*href=["\']https?://([^/"\']+)#i', $content, $hosts);
        $external = collect($hosts[1])
            ->map(fn ($host) => strtolower(preg_replace('/^www\./', '', $host)))
            ->reject(fn ($host) => $host === 'jobgader.com' || str_ends_with($host, '.jobgader.com'))
            ->count();

        $tokens = collect(preg_split('/[^a-z0-9]+/', strtolower((string) $post->title), -1, PREG_SPLIT_NO_EMPTY))
            ->reject(fn ($word) => in_array($word, self::STOPWORDS, true))
            ->unique()
            ->values()
            ->all();

        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'category' => $post->category?->name,
            'status' => $post->status,
            'published_at' => optional($post->published_at)->toDateString(),
            'word_count' => $wordCount,
            'source_links' => $external,
            'has_sources' => (bool) preg_match('#<h[2-4][^>]*>\s*(Sources?|References?)\b#i', $content),
            'noindex' => (bool) $post->noindex,
            'tokens' => $tokens,
            'strength' => $wordCount + 200 * min($external, 10),
            'near_duplicate_of' => null,
            'is_duplicate' => false,
        ];
    }

    /**
     * Two posts are near duplicates when their titles share most of their
     * meaningful words. The stronger post (more words and more source links)
     * is the one to keep; the weaker one points at it.
     *
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function markNearDuplicates(array &$rows, float $threshold): void
    {
        $count = count($rows);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $a = $rows[$i]['tokens'];
                $b = $rows[$j]['tokens'];

                if (count($a) < 3 || count($b) < 3) {
                    continue;
                }

                $shared = count(array_intersect($a, $b));
                $score = $shared / max(1, count(array_unique(array_merge($a, $b))));

                if ($score < $threshold) {
                    continue;
                }

                $weaker = $rows[$i]['strength'] >= $rows[$j]['strength'] ? $j : $i;
                $stronger = $weaker === $j ? $i : $j;

                if ($rows[$weaker]['near_duplicate_of'] === null) {
                    $rows[$weaker]['near_duplicate_of'] = $rows[$stronger]['slug'];
                    $rows[$weaker]['is_duplicate'] = true;
                }
            }
        }
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function flags(array $row, int $minWords): string
    {
        $flags = [];

        if ($row['word_count'] < $minWords) {
            $flags[] = 'short';
        }
        if ($row['source_links'] === 0) {
            $flags[] = 'no_sources';
        }
        if (! $row['has_sources']) {
            $flags[] = 'no_sources_section';
        }
        if ($row['is_duplicate']) {
            $flags[] = 'near_duplicate';
        }

        return implode(';', $flags);
    }

    /**
     * keep, merge into [slug], or noindex. Conservative: a single weak signal
     * alone is only flagged, never actioned.
     *
     * @param  array<string, mixed>  $row
     */
    private function action(array $row): string
    {
        $short = str_contains($row['flags'], 'short');
        $noSources = $row['source_links'] === 0;

        if ($row['is_duplicate']) {
            return $short && $noSources ? 'noindex' : 'merge into '.$row['near_duplicate_of'];
        }

        if ($short && $noSources) {
            return 'noindex';
        }

        return 'keep';
    }
}
