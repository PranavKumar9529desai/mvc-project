<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Farm;
use App\Models\Batch;
use App\Models\StageRecord;

class WoolTrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Farm::factory()
            ->count(5)
            ->has(
                Batch::factory()
                    ->count(3)
                    ->has(StageRecord::factory()->count(4), 'stageRecords'),
                'batches'
            )
            ->create();
    }
}
