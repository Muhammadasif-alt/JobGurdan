<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\JobAlert;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class JobAlertController extends Controller
{
    /** List my job alerts. */
    public function index(Request $request)
    {
        $alerts = $request->user()->jobAlerts()
            ->with(['location', 'category'])
            ->paginate(20);

        return view('seeker.job-alerts.index', compact('alerts'));
    }

    public function create(Request $request)
    {
        return view('seeker.job-alerts.create', [
            'alert' => new JobAlert(['frequency' => 'weekly', 'is_active' => true]),
            'locations' => Location::orderBy('name')->get(['id', 'name']),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateAlert($request);
        $data['user_id'] = $request->user()->id;

        JobAlert::create($data);

        return redirect()->route('seeker.job-alerts.index')
            ->with('success', 'Job alert created — you will receive matching jobs by email.');
    }

    public function edit(Request $request, JobAlert $jobAlert)
    {
        $this->authorizeOwner($request, $jobAlert);

        return view('seeker.job-alerts.edit', [
            'alert' => $jobAlert,
            'locations' => Location::orderBy('name')->get(['id', 'name']),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, JobAlert $jobAlert)
    {
        $this->authorizeOwner($request, $jobAlert);

        $jobAlert->update($this->validateAlert($request));

        return redirect()->route('seeker.job-alerts.index')->with('success', 'Job alert updated.');
    }

    public function destroy(Request $request, JobAlert $jobAlert)
    {
        $this->authorizeOwner($request, $jobAlert);
        $jobAlert->delete();

        return redirect()->route('seeker.job-alerts.index')->with('success', 'Job alert removed.');
    }

    /** Quick toggle for active/paused state. */
    public function toggle(Request $request, JobAlert $jobAlert)
    {
        $this->authorizeOwner($request, $jobAlert);
        $jobAlert->update(['is_active' => ! $jobAlert->is_active]);

        return back()->with('success', $jobAlert->is_active ? 'Alert resumed.' : 'Alert paused.');
    }

    private function validateAlert(Request $request): array
    {
        $data = $request->validate([
            'keywords' => ['nullable', 'string', 'max:200'],
            'location_id' => ['nullable', 'integer', 'exists:locations,id'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'frequency' => ['required', Rule::in(['daily', 'weekly'])],
            'is_active' => ['nullable'],
        ]);

        // Require at least one search criterion
        if (! ($data['keywords'] ?? null) && ! ($data['location_id'] ?? null) && ! ($data['category_id'] ?? null)) {
            throw ValidationException::withMessages([
                'keywords' => 'Please provide at least keywords, a location, or a category.',
            ]);
        }

        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        return $data;
    }

    private function authorizeOwner(Request $request, JobAlert $alert): void
    {
        abort_unless($alert->user_id === $request->user()->id, 403);
    }
}
