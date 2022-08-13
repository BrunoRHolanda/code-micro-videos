<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use App\Models\Genre;
use Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GenreTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateGenre(): void
    {
        /**
         * @var Genre $genre
         */
        $genre = Category::create([
            'name' => 'Test1',
        ]);

        $genre->refresh();

        $this->assertEquals('Test1', $genre->name);
        $this->assertTrue((bool)$genre->is_active);
    }

    public function testUpdateGenre(): void
    {
        /**
         * @var Genre $genre
         */
        $genre = Genre::factory([
            'name' => 'cat 1',
        ])->create();

        $genre->refresh();

        $genre->update([
            'name' => 'cat',
        ]);

        $genre->refresh();

        $this->assertEquals('cat', $genre->name);
        $this->assertTrue((bool)$genre->is_active);
    }

    public function testDeleteGenre(): void
    {
        /**
         * @var Genre $genre
         */
        $genre = Genre::factory()->create();

        $id = $genre->id;

        $genre->delete();

        $result = Genre::find($id);

        $this->assertNull($result);
    }

    public function testUuidGenre()
    {
        /**
         * @var Genre $genre
         */
        $genre = Genre::factory()->create();

        $id = $genre->id;

        $this->assertTrue(Str::isUuid($id));
    }

    public function testRestoreGenre()
    {
        /**
         * @var Genre $genre
         */
        $genre = Genre::factory()->create();

        $id = $genre->id;

        $genre->delete();

        /**
         * @var Genre $genre
         */
        $genre = Genre::onlyTrashed()->find($id);

        $genre->restore();

        $result = Genre::find($id);

        $this->assertNotSoftDeleted($result);
    }
}
