<?php

namespace Tests\Unit;

use App\Models\Batch;
use App\Models\Farm;
use App\Models\StageRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BatchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_farm()
    {
        $farm = Farm::factory()->create();
        $batch = Batch::factory()
            ->for($farm)
            ->create();

        $this->assertTrue($batch->farm instanceof Farm);
        $this->assertEquals($farm->id, $batch->farm->id);
    }

    /** @test */
    public function it_has_stage_records()
    {
        $batch = Batch::factory()
            ->has(StageRecord::factory()->count(3))
            ->create();

        $this->assertCount(3, $batch->stageRecords);
        $this->assertTrue($batch->stageRecords->first() instanceof StageRecord);
    }

    /** @test */
    public function it_can_track_current_stage()
    {
        $batch = Batch::factory()->create(['status' => 'processing']);

        StageRecord::factory()->for($batch)->create([
            'stage' => 'cleaning',
            'completed_at' => now()->subDays(2),
        ]);

        StageRecord::factory()->for($batch)->create([
            'stage' => 'sorting',
            'completed_at' => null,
        ]);

        $latestStage = $batch->stageRecords()->latest()->first();
        $this->assertEquals('sorting', $latestStage->stage);
    }

    /** @test */
    public function it_requires_a_wool_type()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Batch::factory()->create(['wool_type' => null]);
    }

    /** @test */
    public function it_requires_a_weight()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Batch::factory()->create(['weight_kg' => null]);
    }

    /** @test */
    public function it_has_valid_status_values()
    {
        $batch = Batch::factory()->create(['status' => 'processing']);
        $this->assertEquals('processing', $batch->status);

        $batch->update(['status' => 'completed']);
        $this->assertEquals('completed', $batch->status);

        $this->expectException(\Illuminate\Database\QueryException::class);
        $batch->update(['status' => 'invalid_status']);
    }

    /** @test */
    public function it_calculates_processing_duration()
    {
        $batch = Batch::factory()->create([
            'arrival_date' => now()->subDays(5),
        ]);

        StageRecord::factory()->for($batch)->create([
            'stage' => 'cleaning',
            'created_at' => now()->subDays(4),
            'completed_at' => now()->subDays(3),
        ]);

        StageRecord::factory()->for($batch)->create([
            'stage' => 'sorting',
            'created_at' => now()->subDays(3),
            'completed_at' => now()->subDays(1),
        ]);

        $this->assertEquals(4, $batch->stageRecords()
            ->whereNotNull('completed_at')
            ->sum(\DB::raw('DATEDIFF(completed_at, created_at)'))
        );
    }
}