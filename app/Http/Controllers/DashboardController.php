<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Batch;
use App\Models\StageRecord;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Get farms with batch counts
        $farms = Farm::withCount('batches')->get();

        // Get batch statistics
        $batchStats = Batch::select([
            DB::raw('COUNT(*) as total_batches'),
            DB::raw('COUNT(CASE WHEN status != "completed" THEN 1 END) as active_batches'),
            DB::raw('SUM(weight_kg) as total_weight'),
        ])->first();

        // Calculate average stage transition times
        $stageTransitions = StageRecord::select('stage')
            ->selectRaw('AVG(DATEDIFF(completed_at, created_at)) as average_days')
            ->whereNotNull('completed_at')
            ->groupBy('stage')
            ->get();

        $dashboardData = [
            'farms' => $farms,
            'total_batches' => $batchStats->total_batches,
            'active_batches' => $batchStats->active_batches,
            'total_weight' => round($batchStats->total_weight, 2),
            'stage_transitions' => $stageTransitions,
        ];

        return Inertia::render('dashboard', [
            'dashboardData' => $dashboardData,
        ]);
    }
}