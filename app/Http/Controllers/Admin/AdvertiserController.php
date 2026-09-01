<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertiser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'total' => Advertiser::count(),
            'with_jobs' => Advertiser::has('jobs')->count(),
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
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'type' => ['nullable', 'string', 'max:100'],
            'sender_reference' => ['nullable', 'string', 'max:100'],
            'display_reference' => ['nullable', 'string', 'max:100'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('company-logos', 'public');
        }

        Advertiser::create($data);

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
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
            'type' => ['nullable', 'string', 'max:100'],
            'sender_reference' => ['nullable', 'string', 'max:100'],
            'display_reference' => ['nullable', 'string', 'max:100'],
        ]);

        // Delete old logo if user clicked "Remove" or replaced the file
        if (($data['remove_logo'] ?? false) || $request->hasFile('logo')) {
            if ($advertiser->logo) {
                Storage::disk('public')->delete($advertiser->logo);
            }
            $data['logo'] = null;
        }

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('company-logos', 'public');
        }

        unset($data['remove_logo']);
        $advertiser->update($data);

        return redirect()->route('admin.advertisers.index')
            ->with('success', 'Advertiser updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advertiser $advertiser)
    {
        // Clean up uploaded logo when deleting the advertiser
        if ($advertiser->logo) {
            Storage::disk('public')->delete($advertiser->logo);
        }
        $advertiser->delete();

        return redirect()->route('admin.advertisers.index')
            ->with('success', 'Advertiser deleted successfully.');
    }
}
