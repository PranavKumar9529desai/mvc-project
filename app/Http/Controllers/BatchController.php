<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Farm;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batches = Batch::with('farm')->get();
        return Inertia::render('Batches/Index', ['batches' => $batches]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $farms = Farm::all();
        return Inertia::render('Batches/Create', ['farms' => $farms]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_id'      => 'required|exists:farms,id',
            'batch_number' => 'required|string|max:255',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date',
        ]);
    
        Batch::create($validated);
    
        return redirect()->route('batches.index')->with('success', 'Batch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Batch $batch)
    {
        return Inertia::render('Batches/Show', ['batch' => $batch]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Batch $batch)
    {
        $farms = Farm::all();
        return Inertia::render('Batches/Edit', ['batch' => $batch, 'farms' => $farms]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Batch $batch)
    {
        $validated = $request->validate([
            'farm_id'      => 'required|exists:farms,id',
            'batch_number' => 'required|string|max:255',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date',
        ]);
    
        $batch->update($validated);
    
        return redirect()->route('batches.index')->with('success', 'Batch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Batch $batch)
    {
        $batch->delete();
    
        return redirect()->route('batches.index')->with('success', 'Batch deleted successfully.');
    }
}
