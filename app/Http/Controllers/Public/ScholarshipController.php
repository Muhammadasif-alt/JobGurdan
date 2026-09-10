<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    /** Scholarships per listing page: four rows of three. */
    public const PER_PAGE = 12;

    /**
     * The scholarship board: keyword, country and study-level search over
     * published scholarships, twelve to a page.
     */
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('q', ''));
        $country = trim((string) $request->query('country', ''));
        $level = (string) $request->query('level', '');

        if (! array_key_exists($level, Scholarship::STUDY_LEVELS)) {
            $level = '';
        }

        $scholarships = Scholarship::query()
            ->published()
            ->when($keyword !== '', function (Builder $query) use ($keyword) {
                $pattern = '%'.$keyword.'%';

                $query->where(function (Builder $query) use ($pattern) {
                    $query->where('title', 'like', $pattern)
                        ->orWhere('provider', 'like', $pattern)
                        ->orWhere('country', 'like', $pattern)
                        ->orWhere('study_level', 'like', $pattern);
                });
            })
            ->when($country !== '', fn (Builder $query) => $query->where('country', $country))
            ->when($level !== '', fn (Builder $query) => $query->where('study_level', 'like', '%'.Scholarship::STUDY_LEVELS[$level]['match'].'%'))
            ->orderByDesc('is_featured')
            ->latest('published_at')
            ->latest('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('user.scholarships.index', [
            'scholarships' => $scholarships,
            'countries' => Scholarship::query()->published()->distinct()->orderBy('country')->pluck('country'),
            'totalScholarships' => Scholarship::query()->published()->count(),
            'keyword' => $keyword,
            'country' => $country,
            'level' => $level,
        ]);
    }

    /**
     * One scholarship written up in full, with the link out to apply.
     */
    public function show(Scholarship $scholarship): View
    {
        abort_unless($scholarship->isPublished(), 404);

        $moreScholarships = Scholarship::query()
            ->published()
            ->whereKeyNot($scholarship->getKey())
            ->orderByDesc('is_featured')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('user.scholarships.show', [
            'scholarship' => $scholarship,
            'moreScholarships' => $moreScholarships,
        ]);
    }
}
