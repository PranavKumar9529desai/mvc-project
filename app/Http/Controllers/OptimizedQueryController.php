<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Batch;
use App\Models\StageRecord;
use Illuminate\Support\Facades\DB;

class OptimizedQueryController extends Controller
{
    public static function optimizeFarmQueries()
    {
        // Add query logging to help identify issues
        DB::enableQueryLog();

        // Optimize Farm index query
        $optimizedFarmQueries = [
            // Farms with batch counts and total wool weight
            'farms_with_stats' => Farm::query()
                ->withCount('batches')
                ->withSum('batches', 'weight_kg')
                ->with(['batches' => function ($query) {
                    $query->select('id', 'farm_id', 'status')
                        ->where('status', '!=', 'completed');
                }])
                ->get(),

            // Update FarmController index method to use:
            // return Inertia::render('farms/index', [
            //     'farms' => $this->optimizedFarmQueries()['farms_with_stats']
            // ]);
        ];

        return $optimizedFarmQueries;
    }

    public static function optimizeBatchQueries()
    {
        // Optimize Batch index query
        $optimizedBatchQueries = [
            // Batches with farm and latest stage
            'batches_with_relations' => Batch::query()
                ->with(['farm:id,name', 'stageRecords' => function ($query) {
                    $query->latest()->limit(1);
                }])
                ->select([
                    'id',
                    'farm_id',
                    'wool_type',
                    'weight_kg',
                    'status',
                    'arrival_date'
                ])
                ->latest()
                ->get(),

            // Batch show query with optimized related data
            'batch_show' => function($id) {
                return Batch::query()
                    ->with([
                        'farm:id,name,location',
                        'stageRecords' => function ($query) {
                            $query->orderBy('created_at', 'desc');
                        }
                    ])
                    ->findOrFail($id);
            },

            // Dashboard statistics query
            'dashboard_stats' => [
                'batch_stats' => Batch::select([
                    DB::raw('COUNT(*) as total_batches'),
                    DB::raw('COUNT(CASE WHEN status != "completed" THEN 1 END) as active_batches'),
                    DB::raw('SUM(weight_kg) as total_weight')
                ])->first(),

                'farm_stats' => Farm::withCount('batches')
                    ->withSum('batches', 'weight_kg')
                    ->get(),

                'stage_stats' => StageRecord::select('stage')
                    ->selectRaw('AVG(DATEDIFF(completed_at, created_at)) as average_days')
                    ->whereNotNull('completed_at')
                    ->groupBy('stage')
                    ->get()
            ]
        ];

        return $optimizedBatchQueries;
    }

    public static function optimizeStageRecordQueries()
    {
        // Optimize StageRecord queries
        $optimizedStageRecordQueries = [
            // Stage records with batch and farm info
            'stage_records_with_relations' => StageRecord::query()
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
                ->get(),

            // Stage processing timeline
            'processing_timeline' => function($batchId) {
                return StageRecord::query()
                    ->where('batch_id', $batchId)
                    ->select([
                        'id',
                        'stage',
                        'notes',
                        'created_at',
                        'completed_at',
                        DB::raw('DATEDIFF(completed_at, created_at) as duration_days')
                    ])
                    ->orderBy('created_at')
                    ->get();
            }
        ];

        return $optimizedStageRecordQueries;
    }
}