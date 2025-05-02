<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use Inertia\Inertia;
use Illuminate\Http\Request;

class FarmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $farms = Farm::query()
            ->withCount('batches')
            ->withSum('batches', 'weight_kg')
            ->with(['batches' => function ($query) {
                $query->select('id', 'farm_id', 'status')
                    ->where('status', '!=', 'completed');
            }])
            ->get();

        return Inertia::render('farms/index', ['farms' => $farms]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('farms/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FarmRequest $request)
    {
        Farm::create($request->validated());
    
        return redirect()->route('farms.index')->with('success', 'Farm created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Farm $farm)
    {
        $farm->load([
            'batches' => function ($query) {
                $query->with('stageRecords')
                    ->latest();
            }
        ]);
        return Inertia::render('farms/show', ['farm' => $farm]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Farm $farm)
    {
        return Inertia::render('farms/edit', ['farm' => $farm]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FarmRequest $request, Farm $farm)
    {
        $farm->update($request->validated());
    
        return redirect()->route('farms.index')->with('success', 'Farm updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Farm $farm)
    {
        $farm->delete();
    
        return redirect()->route('farms.index')->with('success', 'Farm deleted successfully.');
    }
}
