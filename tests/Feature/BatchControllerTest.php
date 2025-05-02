<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Farm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class BatchControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->farm = Farm::factory()->create();
    }

    /** @test */
    public function it_displays_batches_index_page()
    {
        $batches = Batch::factory()
            ->count(3)
            ->for($this->farm)
            ->create();

        $response = $this->actingAs($this->user)
            ->get(route('batches.index'));

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('batches/index')
            ->has('batches.0', fn (Assert $assert) => $assert
                ->where('id', $batches[0]->id)
                ->where('wool_type', $batches[0]->wool_type)
                ->has('farm')
            )
        );
    }

    /** @test */
    public function it_displays_create_batch_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('batches.create'));

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('batches/create')
            ->has('farm_id', null)
        );
    }

    /** @test */
    public function it_creates_a_batch()
    {
        $batchData = [
            'farm_id' => $this->farm->id,
            'wool_type' => 'Merino',
            'weight_kg' => 100,
            'status' => 'received',
            'arrival_date' => '2025-05-02',
            'notes' => 'Test batch',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('batches.store'), $batchData);

        $response->assertRedirect(route('batches.index'));
        $this->assertDatabaseHas('batches', $batchData);
    }

    /** @test */
    public function it_displays_edit_batch_page()
    {
        $batch = Batch::factory()->for($this->farm)->create();

        $response = $this->actingAs($this->user)
            ->get(route('batches.edit', $batch));

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('batches/edit')
            ->where('batch.id', $batch->id)
            ->where('batch.wool_type', $batch->wool_type)
        );
    }

    /** @test */
    public function it_updates_a_batch()
    {
        $batch = Batch::factory()->for($this->farm)->create();
        $updatedData = [
            'farm_id' => $this->farm->id,
            'wool_type' => 'Updated Wool',
            'weight_kg' => 150,
            'status' => 'processing',
            'arrival_date' => '2025-05-03',
            'notes' => 'Updated notes',
        ];

        $response = $this->actingAs($this->user)
            ->put(route('batches.update', $batch), $updatedData);

        $response->assertRedirect(route('batches.index'));
        $this->assertDatabaseHas('batches', $updatedData);
    }

    /** @test */
    public function it_displays_batch_details()
    {
        $batch = Batch::factory()
            ->for($this->farm)
            ->create();

        $response = $this->actingAs($this->user)
            ->get(route('batches.show', $batch));

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('batches/show')
            ->where('batch.id', $batch->id)
            ->where('batch.wool_type', $batch->wool_type)
            ->has('batch.farm')
            ->has('batch.stage_records')
        );
    }

    /** @test */
    public function it_deletes_a_batch()
    {
        $batch = Batch::factory()->for($this->farm)->create();

        $response = $this->actingAs($this->user)
            ->delete(route('batches.destroy', $batch));

        $response->assertRedirect(route('batches.index'));
        $this->assertDatabaseMissing('batches', ['id' => $batch->id]);
    }

    /** @test */
    public function it_validates_batch_creation()
    {
        $response = $this->actingAs($this->user)
            ->post(route('batches.store'), []);

        $response->assertSessionHasErrors([
            'farm_id',
            'wool_type',
            'weight_kg',
            'status',
            'arrival_date'
        ]);
    }

    /** @test */
    public function it_validates_batch_update()
    {
        $batch = Batch::factory()->for($this->farm)->create();

        $response = $this->actingAs($this->user)
            ->put(route('batches.update', $batch), []);

        $response->assertSessionHasErrors([
            'wool_type',
            'weight_kg',
            'status',
            'arrival_date'
        ]);
    }

    /** @test */
    public function it_validates_batch_status()
    {
        $response = $this->actingAs($this->user)
            ->post(route('batches.store'), [
                'farm_id' => $this->farm->id,
                'wool_type' => 'Test',
                'weight_kg' => 100,
                'status' => 'invalid_status',
                'arrival_date' => '2025-05-02'
            ]);

        $response->assertSessionHasErrors(['status']);
    }
}