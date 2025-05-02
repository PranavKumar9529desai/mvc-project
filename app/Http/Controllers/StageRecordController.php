<?php

namespace App\Http\Controllers;

use App\Models\StageRecord;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Batch;

class StageRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stageRecords = StageRecord::query()
            ->with(['batch:id,farm_id,wool_type', 'batch.farm:id,name'])
            ->select([
                'id',
                'batch_id',
                'stage',
                'notes',
                'created_at',
                'completed_at'
            ])
            ->latest()
            ->get();
        return Inertia::render('StageRecords/Index', ['stageRecords' => $stageRecords]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $batch = Batch::findOrFail($request->batch_id);
        return Inertia::render('StageRecords/Create', ['batch' => $batch]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'stage' => 'required|string|in:cleaning,sorting,scouring,drying,quality_check,packaging',
            'notes' => 'nullable|string',
            'completed_at' => 'nullable|date',
        ]);
    
        StageRecord::create($validated);
    
        $batch = Batch::find($validated['batch_id']);
        return redirect()->route('batches.show', $batch)->with('success', 'Stage record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StageRecord $stageRecord)
    {
        return Inertia::render('StageRecords/Show', ['stageRecord' => $stageRecord]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StageRecord $stageRecord)
    {
        $stageRecord->load([
            'batch' => function ($query) {
                $query->select(['id', 'farm_id', 'wool_type'])
                    ->with('farm:id,name');
            }
        ]);
        return Inertia::render('stage-records/edit', ['stageRecord' => $stageRecord]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StageRecord $stageRecord)
    {
        $validated = $request->validate([
            'stage' => 'required|string|in:cleaning,sorting,scouring,drying,quality_check,packaging',
            'notes' => 'nullable|string',
            'completed_at' => 'nullable|date',
        ]);
    
        $stageRecord->update($validated);
    
        return redirect()->route('batches.show', $stageRecord->batch_id)->with('success', 'Stage record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StageRecord $stageRecord)
    {
        $stageRecord->delete();
    
        return redirect()->route('batches.show', $stageRecord->batch_id)
            ->with('success', 'Stage record deleted successfully.');
    }
}
