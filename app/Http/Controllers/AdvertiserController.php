<?php

namespace App\Http\Controllers;

use App\Models\Advertiser;
use Illuminate\Http\Request;

class AdvertiserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('q', ''));

        $query = Advertiser::withCount('jobs');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('sender_reference', 'like', "%{$search}%");
            });
        }

        $advertisers = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total'      => Advertiser::count(),
            'with_jobs'  => Advertiser::has('jobs')->count(),
            'total_jobs' => \App\Models\Job::count(),
        ];

        return view('admin.advertisers.index', compact('advertisers', 'stats', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.advertisers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:100'],
            'sender_reference' => ['nullable', 'string', 'max:100'],
            'display_reference' => ['nullable', 'string', 'max:100'],
        ]);

        $advertiser = Advertiser::create($data);

        return redirect()->route('admin.advertisers.index')
            ->with('success', 'Advertiser created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Advertiser $advertiser)
    {
        return view('admin.advertisers.show', compact('advertiser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Advertiser $advertiser)
    {
        return view('admin.advertisers.edit', compact('advertiser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Advertiser $advertiser)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:100'],
            'sender_reference' => ['nullable', 'string', 'max:100'],
            'display_reference' => ['nullable', 'string', 'max:100'],
        ]);

        $advertiser->update($data);

        return redirect()->route('admin.advertisers.index')
            ->with('success', 'Advertiser updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advertiser $advertiser)
    {
        // If you need to prevent deletion when related jobs exist, uncomment:
        // if ($advertiser->jobs()->exists()) {
        //     return redirect()->route('admin.advertisers.index')
        //         ->with('error', 'Cannot delete advertiser with related jobs.');
        // }

        $advertiser->delete();

        return redirect()->route('admin.advertisers.index')
            ->with('success', 'Advertiser deleted successfully.');
    }
}
