<?php

namespace Tests\Feature;

use App\Models\Farm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class FarmControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_displays_farms_index_page()
    {
        $farms = Farm::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->get(route('farms.index'));

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('farms/index')
            ->has('farms', 3)
            ->where('farms.0.id', $farms[0]->id)
            ->where('farms.0.name', $farms[0]->name)
        );
    }

    /** @test */
    public function it_displays_create_farm_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('farms.create'));

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('farms/create')
        );
    }

    /** @test */
    public function it_creates_a_farm()
    {
        $farmData = [
            'name' => 'Test Farm',
            'location' => 'Test Location',
            'contact_person' => 'John Doe',
            'contact_number' => '1234567890',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('farms.store'), $farmData);

        $response->assertRedirect(route('farms.index'));
        $this->assertDatabaseHas('farms', $farmData);
    }

    /** @test */
    public function it_displays_edit_farm_page()
    {
        $farm = Farm::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('farms.edit', $farm));

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('farms/edit')
            ->where('farm.id', $farm->id)
            ->where('farm.name', $farm->name)
        );
    }

    /** @test */
    public function it_updates_a_farm()
    {
        $farm = Farm::factory()->create();
        $updatedData = [
            'name' => 'Updated Farm',
            'location' => 'Updated Location',
            'contact_person' => 'Jane Doe',
            'contact_number' => '0987654321',
        ];

        $response = $this->actingAs($this->user)
            ->put(route('farms.update', $farm), $updatedData);

        $response->assertRedirect(route('farms.index'));
        $this->assertDatabaseHas('farms', $updatedData);
    }

    /** @test */
    public function it_displays_farm_details()
    {
        $farm = Farm::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('farms.show', $farm));

        $response->assertInertia(fn (Assert $assert) => $assert
            ->component('farms/show')
            ->where('farm.id', $farm->id)
            ->where('farm.name', $farm->name)
        );
    }

    /** @test */
    public function it_deletes_a_farm()
    {
        $farm = Farm::factory()->create();

        $response = $this->actingAs($this->user)
            ->delete(route('farms.destroy', $farm));

        $response->assertRedirect(route('farms.index'));
        $this->assertDatabaseMissing('farms', ['id' => $farm->id]);
    }

    /** @test */
    public function it_validates_farm_creation()
    {
        $response = $this->actingAs($this->user)
            ->post(route('farms.store'), []);

        $response->assertSessionHasErrors(['name', 'location']);
    }

    /** @test */
    public function it_validates_farm_update()
    {
        $farm = Farm::factory()->create();

        $response = $this->actingAs($this->user)
            ->put(route('farms.update', $farm), []);

        $response->assertSessionHasErrors(['name', 'location']);
    }
}