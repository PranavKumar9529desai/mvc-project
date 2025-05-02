<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Farm;
use App\Models\StageRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class StageRecordControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->farm = Farm::factory()->create();
        $this->batch = Batch::factory()->for($this->farm)->create();
    }

    /** @test */
    public function it_displays_create_stage_record_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('stage-records.create', ['batch' => $this->batch->id]));

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('stage-records/create')
            ->has('batch')
            ->where('batch.id', $this->batch->id)
        );
    }

    /** @test */
    public function it_creates_a_stage_record()
    {
        $stageData = [
            'batch_id' => $this->batch->id,
            'stage' => 'cleaning',
            'notes' => 'Test stage record',
            'completed_at' => null,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('stage-records.store'), $stageData);

        $response->assertRedirect(route('batches.show', $this->batch));
        $this->assertDatabaseHas('stage_records', $stageData);
    }

    /** @test */
    public function it_displays_edit_stage_record_page()
    {
        $stageRecord = StageRecord::factory()
            ->for($this->batch)
            ->create();

        $response = $this->actingAs($this->user)
            ->get(route('stage-records.edit', $stageRecord));

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('stage-records/edit')
            ->where('stageRecord.id', $stageRecord->id)
            ->where('stageRecord.stage', $stageRecord->stage)
        );
    }

    /** @test */
    public function it_updates_a_stage_record()
    {
        $stageRecord = StageRecord::factory()
            ->for($this->batch)
            ->create();

        $updatedData = [
            'stage' => 'sorting',
            'notes' => 'Updated notes',
            'completed_at' => '2025-05-03 10:00:00',
        ];

        $response = $this->actingAs($this->user)
            ->put(route('stage-records.update', $stageRecord), $updatedData);

        $response->assertRedirect(route('batches.show', $this->batch));
        $this->assertDatabaseHas('stage_records', [
            'id' => $stageRecord->id,
            ...$updatedData,
        ]);
    }

    /** @test */
    public function it_deletes_a_stage_record()
    {
        $stageRecord = StageRecord::factory()
            ->for($this->batch)
            ->create();

        $response = $this->actingAs($this->user)
            ->delete(route('stage-records.destroy', $stageRecord));

        $response->assertRedirect(route('batches.show', $this->batch));
        $this->assertDatabaseMissing('stage_records', ['id' => $stageRecord->id]);
    }

    /** @test */
    public function it_validates_stage_record_creation()
    {
        $response = $this->actingAs($this->user)
            ->post(route('stage-records.store'), []);

        $response->assertSessionHasErrors([
            'batch_id',
            'stage',
        ]);
    }

    /** @test */
    public function it_validates_stage_record_update()
    {
        $stageRecord = StageRecord::factory()
            ->for($this->batch)
            ->create();

        $response = $this->actingAs($this->user)
            ->put(route('stage-records.update', $stageRecord), []);

        $response->assertSessionHasErrors([
            'stage',
        ]);
    }

    /** @test */
    public function it_validates_stage_values()
    {
        $response = $this->actingAs($this->user)
            ->post(route('stage-records.store'), [
                'batch_id' => $this->batch->id,
                'stage' => 'invalid_stage',
            ]);

        $response->assertSessionHasErrors(['stage']);
    }

    /** @test */
    public function it_validates_completed_at_date_format()
    {
        $response = $this->actingAs($this->user)
            ->post(route('stage-records.store'), [
                'batch_id' => $this->batch->id,
                'stage' => 'cleaning',
                'completed_at' => 'invalid-date',
            ]);

        $response->assertSessionHasErrors(['completed_at']);
    }
}