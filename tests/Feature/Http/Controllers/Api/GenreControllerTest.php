<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Lang;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $genre = Genre::factory()->count(100)->create();

        $response = $this->get(route('genres.index'));

        $response
            ->assertStatus(200)
            ->assertJson($genre->toArray());
    }

    public function testShow()
    {
        $genre = Genre::factory()->create();

        $response = $this->get(route('genres.show', ['genre' => $genre->id]));

        $response
            ->assertStatus(200)
            ->assertJson($genre->toArray());
    }

    public function testInvalidationData()
    {
        $response = $this->json('POST', route('genres.store'), []);

        $this->assertValidationRequired($response);

        $genre = Genre::factory()->create();
        $response = $this->json('PUT',
            route('genres.update',
                ['genre' => $genre->id]),
            [
                'name' => str_repeat('a', 256),
            ]
        );

        $this->assertValidationMaxString($response);
    }

    protected function assertValidationMaxString(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonFragment([
                Lang::get('validation.max.string', ['attribute' => 'name', 'max' => 255])
            ]);
    }

    protected function assertValidationRequired(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonFragment([
                Lang::get('validation.required', ['attribute' => 'name'])
            ]);
    }

    public function testStore(): void
    {
        $response = $this->json('POST', route('genres.store'), [
            'name' => 'test'
        ]);

        $id = $response->json('id');

        $genre = Genre::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($genre->toArray());

        $this->assertTrue((bool)$response->json('is_active'));

        $response = $this->json('POST', route('genres.store'), [
            'name' => 'test',
            'is_active' => false
        ]);

        $response
            ->assertJsonFragment([
                'is_active' => 0
            ]);
    }

    public function testUpdate(): void
    {
        $genre = Genre::factory()->create([
            'name' => 'test'
        ]);

        $response = $this->json(
            'PUT',
            route('genres.update', ['genre' => $genre->id]),
            [
                'name' => 'test edited'
            ]
        );

        $id = $response->json('id');

        $genre = Genre::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($genre->toArray())
            ->assertJsonFragment([
                'name' => 'test edited'
            ]);
    }

    public function testDestroy(): void
    {
        $genre = Genre::factory()->create([
            'name' => 'test'
        ]);

        $response = $this->json(
            'DELETE',
            route('genres.destroy', ['genre' => $genre->id])
        );

        $genre = Genre::find($genre->id);

        $response->assertStatus(204);

        $this->assertNull($genre);
    }
}
