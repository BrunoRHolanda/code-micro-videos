<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private Genre $genre;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = Genre::factory()->create();

        $this->genre->categories()->sync([Category::factory()->create()->id]);
    }

    public function testIndex()
    {
        $response = $this->get(route('genres.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->genre->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('genres.show', ['genre' => $this->genre->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->genre->toArray());
    }

    public function testFieldNameValidation()
    {
        $missingNameInDataValidation = [];

        $nameWitchNumberInDataValidation = [
            'name' => 123,
        ];

        $bigStringInFieldNameToDataValidation = [
            'name' => str_repeat('a', 300),
        ];

        $expectedInvalidFields = [
            'name'
        ];

        $this->assertValidationErrorsInStore(
            $missingNameInDataValidation,
            $expectedInvalidFields,
            'required'
        );
        $this->assertValidationErrorsInStore(
            $nameWitchNumberInDataValidation,
            $expectedInvalidFields,
            'string'
        );
        $this->assertValidationErrorsInStore(
            $bigStringInFieldNameToDataValidation,
            $expectedInvalidFields,
            'max.string',
            [
                'max' => 255
            ]
        );
    }

    public function testStore(): void
    {
        $category = Category::factory()->create();

        $data = [
            'name' => 'test',
            'categories' => [$category->id]
        ];
        $expected = [
            'name' => $data['name'],
            'is_active' => 1,
            'deleted_at' => null
        ];
        $this->assertStore($data, $expected);

        $data = [
            'name' => 'test',
            'categories' => [$category->id],
            'is_active' => 0
        ];
        $expected = [
            'name' => $data['name'],
            'is_active' => $data['is_active'],
            'deleted_at' => null
        ];
        $this->assertStore($data, $expected);
    }

    public function testUpdate(): void
    {
        $this->genre = Genre::factory()->create([
            'is_active' => 0
        ]);
        $this->genre->categories()->sync([Category::factory()->create()->id]);

        $data = [
            'name' => 'test',
            'is_active' => 1
        ];
        $this->assertUpdate(
            $data,
            $data + [
                'deleted_at' => null
            ]
        );
    }

    public function testDestroy(): void
    {
        $response = $this->json(
            'DELETE',
            route('genres.destroy', ['genre' => $this->genre->id])
        );

        $category = Genre::find($this->genre->id);

        $response->assertStatus(204);

        $this->assertNull($category);
    }

    protected function routeStore()
    {
        return route('genres.store');
    }

    protected function routeUpdate()
    {
        return route('genres.update', ['genre' => $this->genre->id]);
    }

    protected function model()
    {
        return Genre::class;
    }
}
