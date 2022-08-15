<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::factory()->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->category->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('categories.show', ['category' => $this->category->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->category->toArray());
    }

    public function testInvalidationData()
    {
        $data = [
            'name' => ''
        ];
        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'string');

        $data = [
            'name' => str_repeat('a', 256),
        ];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max' => 255]);

        $data = [
            'is_active' => 'a'
        ];
        $this->assertInvalidationInStoreAction($data, 'boolean');
        $this->assertInvalidationInUpdateAction($data, 'boolean');
    }

    public function testStore(): void
    {
        $data = [
            'name' => 'test'
        ];
        $this->assertStore(
            $data,
            $data + [
                'description' => null,
                'is_active' => 1,
                'deleted_at' => null
            ]
        );

        $data = [
            'name' => 'test',
            'description' => 'description',
            'is_active' => 0
        ];
        $this->assertStore($data, $data + ['deleted_at' => null]);
    }

    public function testUpdate(): void
    {
        $this->category = Category::factory()->create([
            'description' => 'description',
            'is_active' => 0
        ]);
        $data = [
            'name' => 'test edited'
        ];
        $this->assertUpdate(
            $data,
            $data + [
                'description' => 'description',
                'is_active' => 0,
                'deleted_at' => null
            ]
        );
    }

    public function testDestroy(): void
    {
        $response = $this->json(
            'DELETE',
            route('categories.destroy', ['category' => $this->category->id])
        );

        $category = Category::find($this->category->id);

        $response->assertStatus(204);

        $this->assertNull($category);
    }

    protected function routeStore()
    {
        return route('categories.store');
    }

    protected function routeUpdate()
    {
        return route('categories.update', ['category' => $this->category->id]);
    }

    protected function model()
    {
        return Category::class;
    }
}
