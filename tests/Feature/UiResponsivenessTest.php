<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Farm;
use App\Models\Batch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UiResponsivenessTest extends TestCase
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
    public function dashboard_includes_responsive_meta_tag()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertSee('viewport', false);
        $response->assertSee('width=device-width, initial-scale=1.0', false);
    }

    /** @test */
    public function layout_uses_responsive_grid_classes()
    {
        $response = $this->actingAs($this->user)
            ->get(route('farms.index'));

        // Check for responsive grid classes
        $response->assertSee('grid gap-4 md:grid-cols-2 lg:grid-cols-3', false);
    }

    /** @test */
    public function navigation_includes_mobile_menu()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        // Check for mobile navigation components
        $response->assertSee('lg:hidden', false); // Mobile menu button class
        $response->assertSee('hidden lg:flex', false); // Desktop menu class
    }

    /** @test */
    public function forms_are_responsive()
    {
        $response = $this->actingAs($this->user)
            ->get(route('farms.create'));

        // Check for responsive form classes
        $response->assertSee('max-w-2xl', false);
        $response->assertSee('w-full', false);
    }

    /** @test */
    public function tables_are_responsive()
    {
        $response = $this->actingAs($this->user)
            ->get(route('batches.index'));

        // Check for responsive table wrapper
        $response->assertSee('overflow-x-auto', false);
    }

    /** @test */
    public function cards_use_responsive_padding()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        // Check for responsive padding classes
        $response->assertSee('p-4 sm:p-6', false);
    }

    /** @test */
    public function buttons_have_appropriate_touch_targets()
    {
        $response = $this->actingAs($this->user)
            ->get(route('farms.index'));

        // Check for appropriate button padding for touch targets
        $response->assertSee('px-4 py-2', false);
    }

    /** @test */
    public function text_is_readable_at_all_breakpoints()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        // Check for responsive typography classes
        $response->assertSee('text-sm md:text-base', false);
        $response->assertSee('text-lg md:text-xl', false);
    }

    /** @test */
    public function timeline_is_responsive()
    {
        $response = $this->actingAs($this->user)
            ->get(route('batches.show', $this->batch));

        // Check for responsive timeline classes
        $response->assertSee('space-y-6', false);
        $response->assertSee('pl-8', false);
    }

    /** @test */
    public function statistics_cards_are_responsive()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        // Check for responsive grid layout for statistics cards
        $response->assertSee('grid gap-4 md:grid-cols-3', false);
    }
}