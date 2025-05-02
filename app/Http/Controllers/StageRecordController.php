<?php

namespace App\Http\Controllers;

use App\Models\StageRecord;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Batch;
use App\Http\Requests\StageRecordRequest;

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
                'completion_date'
            ])
            ->latest()
            ->get();
        return Inertia::render('stage-records/index', ['stageRecords' => $stageRecords]);
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
    public function store(StageRecordRequest $request)
    {
        $validated = $request->validated();
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
        return Inertia::render('StageRecords/Edit', ['stageRecord' => $stageRecord]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StageRecordRequest $request, StageRecord $stageRecord)
    {
        $validated = $request->validated();
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
