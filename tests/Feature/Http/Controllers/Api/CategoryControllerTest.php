<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Lang;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations;

    public function testIndex()
    {
        $category = Category::factory()->count(100)->create();

        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function testShow()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('categories.show', ['category' => $category->id]));

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray());
    }

    public function testInvalidationData()
    {
        $response = $this->json('POST', route('categories.store'), []);
        $this->assertValidationRequired($response);

        $category = Category::factory()->create();
        $response = $this->json('PUT',
            route('categories.update',
                ['category' => $category->id]),
            [
                'name' => str_repeat('a', 256),
            ]
        );

        $this->assertValidationMaxString($response);
    }

    protected function assertValidationMaxString(TestResponse $response)
    {
        $this->assertInvalidationFields(
            $response,
            ['name'],
            'max.string',
            ['max' => 255]
        );
    }

    protected function assertValidationRequired(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['name'], 'required');
    }

    public function testStore(): void
    {
        $response = $this->json('POST', route('categories.store'), [
            'name' => 'test'
        ]);

        $id = $response->json('id');

        $category = Category::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($category->toArray());

        $this->assertTrue((bool)$response->json('is_active'));
        $this->assertNull($response->json('description'));

        $response = $this->json('POST', route('categories.store'), [
            'name' => 'test',
            'description' => 'description',
            'is_active' => false
        ]);

        $response
            ->assertJsonFragment([
                'description' => 'description'
            ])
            ->assertJsonFragment([
                'is_active' => 0
            ]);
    }

    public function testUpdate(): void
    {
        $category = Category::factory()->create([
            'name' => 'test'
        ]);

        $response = $this->json(
            'PUT',
            route('categories.update', ['category' => $category->id]),
            [
                'name' => 'test edited'
            ]
        );

        $id = $response->json('id');

        $category = Category::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                'name' => 'test edited'
            ]);
    }

    public function testDestroy(): void
    {
        $category = Category::factory()->create([
            'name' => 'test'
        ]);

        $response = $this->json(
            'DELETE',
            route('categories.destroy', ['category' => $category->id])
        );

        $category = Category::find($category->id);

        $response->assertStatus(204);

        $this->assertNull($category);
    }
}
