<?php

namespace Tests\Unit;

use App\Models\Farm;
use App\Models\Batch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FarmTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_batches()
    {
        $farm = Farm::factory()
            ->has(Batch::factory()->count(3))
            ->create();

        $this->assertCount(3, $farm->batches);
        $this->assertTrue($farm->batches->first() instanceof Batch);
    }

    /** @test */
    public function it_can_calculate_total_wool_weight()
    {
        $farm = Farm::factory()
            ->has(Batch::factory()->count(3)->state([
                'weight_kg' => 100
            ]))
            ->create();

        $this->assertEquals(300, $farm->batches->sum('weight_kg'));
    }

    /** @test */
    public function it_can_count_active_batches()
    {
        $farm = Farm::factory()
            ->has(Batch::factory()->count(2)->state(['status' => 'processing']))
            ->has(Batch::factory()->count(1)->state(['status' => 'completed']))
            ->create();

        $this->assertEquals(2, $farm->batches->where('status', '!=', 'completed')->count());
    }

    /** @test */
    public function it_requires_a_name()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Farm::factory()->create(['name' => null]);
    }

    /** @test */
    public function it_requires_a_location()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Farm::factory()->create(['location' => null]);
    }
}