<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('q', ''));

        $query = Location::withCount('jobs');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('area', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('postal_code', 'like', "%{$search}%");
            });
        }

        $locations = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total'     => Location::count(),
            'states'    => Location::select('name')->distinct()->count('name'),
            'with_jobs' => Location::has('jobs')->count(),
        ];

        return view('admin.locations.index', compact('locations', 'stats', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'area' => ['nullable', 'string', 'max:191'],
            'country' => ['nullable', 'string', 'max:191'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        Location::create($data);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        return view('admin.locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'area' => ['nullable', 'string', 'max:191'],
            'country' => ['nullable', 'string', 'max:191'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        $location->update($data);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        // Optionally prevent deletion if jobs exist
        // if ($location->jobs()->exists()) {
        //     return redirect()->route('admin.locations.index')
        //         ->with('error', 'Cannot delete location with related jobs.');
        // }

        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location deleted successfully.');
    }
}
