<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateCategory(): void
    {
        /**
         * @var Category $category
         */
        $category = Category::create([
            'name' => 'Test1',
        ]);

        $category->refresh();

        $this->assertEquals('Test1', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue((bool)$category->is_active);
    }

    public function testUpdateCategory(): void
    {
        /**
         * @var Category $category
         */
        $category = Category::factory([
            'name' => 'cat 1',
            'description' => 'a cat 1 category'
        ])->create();

        $category->refresh();

        $category->update([
            'name' => 'cat',
        ]);

        $category->refresh();

        $this->assertEquals('cat', $category->name);
        $this->assertTrue((bool)$category->is_active);
    }

    public function testDeleteCategory(): void
    {
        /**
         * @var Category $category
         */
        $category = Category::factory()->create();

        $id = $category->id;

        $category->delete();

        $result = Category::find($id);

        $this->assertNull($result);
    }

    public function testUuidCategory()
    {
        /**
         * @var Category $category
         */
        $category = Category::factory()->create();

        $id = $category->id;

        $this->assertTrue(Str::isUuid($id));
    }

    public function testRestoreCategory()
    {
        /**
         * @var Category $category
         */
        $category = Category::factory()->create();

        $id = $category->id;

        $category->delete();

        /**
         * @var Category $category
         */
        $category = Category::onlyTrashed()->find($id);

        $category->restore();

        $result = Category::find($id);

        $this->assertNotSoftDeleted($result);
    }
}
