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
        $stageRecords = StageRecord::with('batch')->get();
        return Inertia::render('StageRecords/Index', ['stageRecords' => $stageRecords]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $batches = Batch::all();
        return Inertia::render('StageRecords/Create', ['batches' => $batches]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'stage'    => 'required|string|max:255',
            'notes'    => 'nullable|string',
        ]);
    
        StageRecord::create($validated);
    
        return redirect()->route('stage-records.index')->with('success', 'Stage record created successfully.');
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
        $batches = Batch::all();
        return Inertia::render('StageRecords/Edit', ['stageRecord' => $stageRecord, 'batches' => $batches]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StageRecord $stageRecord)
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'stage'    => 'required|string|max:255',
            'notes'    => 'nullable|string',
        ]);
    
        $stageRecord->update($validated);
    
        return redirect()->route('stage-records.index')->with('success', 'Stage record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StageRecord $stageRecord)
    {
        $stageRecord->delete();
    
        return redirect()->route('stage-records.index')->with('success', 'Stage record deleted successfully.');
    }
}
