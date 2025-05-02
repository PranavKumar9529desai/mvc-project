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
        $totalBatches = Batch::count();
        $activeBatches = Batch::whereNotIn('id', function ($query) {
            $query->select('batch_id')
                  ->from('stage_records')
                  ->where('stage', 'completed');
        })->count();

        // Calculate average stage transition times
        // Note: The original query used DATEDIFF which is MySQL specific.
        // Using a more database-agnostic approach with Carbon diffInDays.
        // This requires fetching the records first.
        $completedStages = StageRecord::whereNotNull('completed_at')
            ->select('stage', 'created_at', 'completed_at') // Select necessary columns
            ->get();

        $stageTransitions = $completedStages->groupBy('stage')
            ->map(function ($records, $stage) {
                $totalDays = $records->sum(function ($record) {
                    // Ensure dates are Carbon instances for diffInDays
                    $createdAt = \Carbon\Carbon::parse($record->created_at);
                    $completedAt = \Carbon\Carbon::parse($record->completed_at);
                    return $completedAt->diffInDays($createdAt);
                });
                return [
                    'stage' => $stage,
                    'average_days' => round($totalDays / $records->count(), 2)
                ];
            })->values(); // Use values() to reset keys for JSON array

        $dashboardData = [
            'farms' => $farms,
            'total_batches' => $totalBatches,
            'active_batches' => $activeBatches,
            // 'total_weight' removed
            'stage_transitions' => $stageTransitions,
        ];

        return Inertia::render('dashboard', [
            'dashboardData' => $dashboardData,
        ]);
    }
}