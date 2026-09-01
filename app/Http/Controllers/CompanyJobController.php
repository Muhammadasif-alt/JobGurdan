<?php

namespace App\Http\Controllers;

use App\Models\Advertiser;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Company-side job CRUD. A company user only ever sees / edits / deletes
 * jobs whose user_id matches their own — admin-posted or other-company
 * jobs are invisible to them.
 */
class CompanyJobController extends Controller
{
    /** Find-or-create the Advertiser record that represents the current company. */
    protected function advertiserForCompany(): Advertiser
    {
        $user = Auth::user();
        return Advertiser::firstOrCreate(
            ['name' => $user->name],
            [
                'website'     => null,
                'description' => 'Company account on Jobs in USA',
            ]
        );
    }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $search = trim((string) $request->input('q', ''));

        $query = Job::with(['category', 'location', 'advertiser'])
            ->where('user_id', $userId);

        if ($search !== '') {
            $query->where('position', 'like', "%{$search}%");
        }

        $jobs = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $stats = [
            'total'       => Job::where('user_id', $userId)->count(),
            'active'      => Job::where('user_id', $userId)
                                ->where(fn($q) => $q->where('status', 'active')->orWhereNull('status'))
                                ->count(),
            'this_week'   => Job::where('user_id', $userId)
                                ->where('created_at', '>=', now()->subWeek())
                                ->count(),
        ];

        return view('company.jobs.index', compact('jobs', 'stats', 'search'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $locations  = Location::orderBy('name')->get(['id', 'name']);
        return view('company.jobs.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'position'         => ['required', 'string', 'max:191'],
            'category_id'      => ['nullable', 'exists:categories,id'],
            'location_id'      => ['nullable', 'exists:locations,id'],
            'employment_type'  => ['nullable', 'string', 'max:50'],
            'job_type'         => ['nullable', 'string', 'max:50'],
            'work_hours'       => ['nullable', 'string', 'max:50'],
            'salary_minimum'   => ['nullable', 'numeric', 'min:0'],
            'salary_maximum'   => ['nullable', 'numeric', 'min:0'],
            'salary_currency'  => ['nullable', 'string', 'max:10'],
            'salary_period'    => ['nullable', 'string', 'max:30'],
            'application_url'  => ['nullable', 'url', 'max:255'],
            'description'      => ['nullable', 'string'],
            'status'           => ['nullable', 'in:active,draft,closed'],
        ]);

        $data['user_id']       = Auth::id();
        $data['advertiser_id'] = $this->advertiserForCompany()->id;
        $data['status']        = $data['status'] ?? 'active';

        Job::create($data);

        return redirect()->route('company.jobs.index')
            ->with('success', 'Job posted successfully — candidates can now find it.');
    }

    public function edit(Job $job)
    {
        abort_unless($job->user_id === Auth::id(), 403);

        $categories = Category::orderBy('name')->get(['id', 'name']);
        $locations  = Location::orderBy('name')->get(['id', 'name']);

        return view('company.jobs.edit', compact('job', 'categories', 'locations'));
    }

    public function update(Request $request, Job $job)
    {
        abort_unless($job->user_id === Auth::id(), 403);

        $data = $request->validate([
            'position'         => ['required', 'string', 'max:191'],
            'category_id'      => ['nullable', 'exists:categories,id'],
            'location_id'      => ['nullable', 'exists:locations,id'],
            'employment_type'  => ['nullable', 'string', 'max:50'],
            'job_type'         => ['nullable', 'string', 'max:50'],
            'work_hours'       => ['nullable', 'string', 'max:50'],
            'salary_minimum'   => ['nullable', 'numeric', 'min:0'],
            'salary_maximum'   => ['nullable', 'numeric', 'min:0'],
            'salary_currency'  => ['nullable', 'string', 'max:10'],
            'salary_period'    => ['nullable', 'string', 'max:30'],
            'application_url'  => ['nullable', 'url', 'max:255'],
            'description'      => ['nullable', 'string'],
            'status'           => ['nullable', 'in:active,draft,closed'],
        ]);

        $job->update($data);

        return redirect()->route('company.jobs.index')
            ->with('success', 'Job updated.');
    }

    public function destroy(Job $job)
    {
        abort_unless($job->user_id === Auth::id(), 403);

        $job->delete();

        return redirect()->route('company.jobs.index')
            ->with('success', 'Job deleted.');
    }

    /** Company profile (placeholder using the user's account info). */
    public function profile()
    {
        $user = Auth::user();
        $advertiser = Advertiser::where('name', $user->name)->first();

        $stats = [
            'total_jobs' => Job::where('user_id', $user->id)->count(),
            'active'     => Job::where('user_id', $user->id)
                               ->where(fn($q) => $q->where('status', 'active')->orWhereNull('status'))
                               ->count(),
        ];

        return view('company.profile', compact('user', 'advertiser', 'stats'));
    }
}
