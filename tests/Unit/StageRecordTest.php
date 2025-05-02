<?php

namespace Tests\Unit;

use App\Models\Batch;
use App\Models\StageRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageRecordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_batch()
    {
        $batch = Batch::factory()->create();
        $stageRecord = StageRecord::factory()
            ->for($batch)
            ->create();

        $this->assertTrue($stageRecord->batch instanceof Batch);
        $this->assertEquals($batch->id, $stageRecord->batch->id);
    }

    /** @test */
    public function it_requires_a_stage()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        StageRecord::factory()->create(['stage' => null]);
    }

    /** @test */
    public function it_accepts_valid_stages()
    {
        $validStages = [
            'cleaning',
            'sorting',
            'scouring',
            'drying',
            'quality_check',
            'packaging'
        ];

        foreach ($validStages as $stage) {
            $stageRecord = StageRecord::factory()->create(['stage' => $stage]);
            $this->assertEquals($stage, $stageRecord->stage);
        }

        $this->expectException(\Illuminate\Database\QueryException::class);
        StageRecord::factory()->create(['stage' => 'invalid_stage']);
    }

    /** @test */
    public function it_can_track_completion_status()
    {
        $stageRecord = StageRecord::factory()->create([
            'completed_at' => null
        ]);

        $this->assertNull($stageRecord->completed_at);
        $this->assertFalse($stageRecord->isCompleted());

        $stageRecord->update(['completed_at' => now()]);
        $this->assertNotNull($stageRecord->completed_at);
        $this->assertTrue($stageRecord->isCompleted());
    }

    /** @test */
    public function it_can_calculate_duration()
    {
        $startDate = now()->subDays(3);
        $endDate = now()->subDay();

        $stageRecord = StageRecord::factory()->create([
            'created_at' => $startDate,
            'completed_at' => $endDate
        ]);

        $this->assertEquals(2, $stageRecord->duration_days);
    }

    /** @test */
    public function it_returns_null_duration_when_not_completed()
    {
        $stageRecord = StageRecord::factory()->create([
            'created_at' => now()->subDays(3),
            'completed_at' => null
        ]);

        $this->assertNull($stageRecord->duration_days);
    }
}