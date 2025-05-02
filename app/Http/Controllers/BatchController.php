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
        $batches = Batch::query()
            ->with(['farm:id,name', 'stageRecords' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->select([
                'id',
                'farm_id',
                'batch_number',
                'start_date',
                'end_date'
            ])
            ->latest()
            ->get();
        return Inertia::render('batches/index', ['batches' => $batches]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $farms = Farm::select(['id', 'name'])->orderBy('name')->get();
        return Inertia::render('batches/create', ['farms' => $farms]);
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
        $batch->load([
            'farm:id,name,location',
            'stageRecords' => function ($query) {
                $query->orderBy('created_at', 'desc')
                    ->select([
                        'id',
                        'batch_id',
                        'stage',
                        'notes',
                        'created_at',
                        'completed_at'
                    ]);
            }
        ]);
        return Inertia::render('batches/show', ['batch' => $batch]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Batch $batch)
    {
        $farms = Farm::select(['id', 'name'])->orderBy('name')->get();
        return Inertia::render('batches/edit', ['batch' => $batch, 'farms' => $farms]);
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
