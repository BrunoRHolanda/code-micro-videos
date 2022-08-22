<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use App\Models\Category;
use App\Models\Enums\Rating;
use App\Models\Genre;
use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class VideoControllerTest extends TestCase
{
    use DatabaseMigrations, TestSaves, TestValidations;

    protected Video $video;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = Video::factory()->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('videos.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->video->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('videos.show', ['video' => $this->video->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->video->toArray());
    }

    public function testStore(): void
    {
        /**
         * @var Category $category
         */
        $category = Category::factory()->create();
        /**
         * @var Genre $genre
         */
        $genre = Genre::factory()->create();

        $genre->categories()->sync([$category->id]);

        $data = [
            'title' => 'asd',
            'description' => 'asdasdasdasdasd',
            'year_launched' => rand(1985, 2022),
            'opened' => true,
            'rating' => Rating::Free,
            'duration' => rand(1, 30),
        ];
        $dependencies = [
            'categories' => [$category->id],
            'genres' => [$genre->id]
        ];

        $this->assertStore(
            $data + $dependencies,
            $data + ['deleted_at' => null]
        );

        $data = [
            'title' => 'asd',
            'description' => 'asdasdasdasdasd',
            'year_launched' => rand(1985, 2022),
            'opened' => true,
            'rating' => Rating::Eighteen,
            'duration' => rand(1, 30),
        ];

        $this->assertStore($data + $dependencies, $data + ['deleted_at' => null]);
    }

    public function testVideoSizeValidation()
    {
        /**
         * @var Category $category
         */
        $category = Category::factory()->create();
        /**
         * @var Genre $genre
         */
        $genre = Genre::factory()->create();

        $genre->categories()->sync([$category->id]);

        $file1 = UploadedFile::fake()->create('video1.mp4', 4098);

        $data = [
            'title' => 'asd',
            'description' => 'asdasdasdasdasd',
            'year_launched' => rand(1985, 2022),
            'opened' => true,
            'rating' => Rating::Free,
            'duration' => rand(1, 30),
        ];
        $dependencies = [
            'categories' => [$category->id],
            'genres' => [$genre->id],
            'video_file' => $file1
        ];

        $this->assertValidationErrorsInStore(
            $data + $dependencies,
            [
                'video_file'
            ],
            'size.file',
            [
                'size' => 2048
            ]
        );
    }

    public function testVideoMimeTypeValidation()
    {
        /**
         * @var Category $category
         */
        $category = Category::factory()->create();
        /**
         * @var Genre $genre
         */
        $genre = Genre::factory()->create();

        $genre->categories()->sync([$category->id]);

        $file1 = UploadedFile::fake()->create('video2.mp4', 1024, 'video/avi');

        $data = [
            'title' => 'asd',
            'description' => 'asdasdasdasdasd',
            'year_launched' => rand(1985, 2022),
            'opened' => true,
            'rating' => Rating::Free,
            'duration' => rand(1, 30),
        ];
        $dependencies = [
            'categories' => [$category->id],
            'genres' => [$genre->id],
            'video_file' => $file1
        ];

        $this->assertValidationErrorsInStore(
            $data + $dependencies,
            [
                'video_file'
            ],
            'mimetypes',
            [
                'values' => 'video/mp4'
            ]
        );
    }

    public function testCategoryValidation()
    {
        /**
         * @var Category $category
         * @var Category $category2
         */
        $category = Category::factory()->create();
        $category2 = Category::factory()->create();
        /**
         * @var Genre $genre
         */
        $genre = Genre::factory()->create();

        $genre->categories()->sync([$category->id]);

        $data = [
            'title' => 'asd',
            'description' => 'asdasdasdasdasd',
            'year_launched' => rand(1985, 2022),
            'opened' => true,
            'rating' => Rating::Free,
            'duration' => rand(1, 30),
            'categories' => [$category->id, $category2->id],
            'genres' => [$genre->id]
        ];

        $response = $this->postJson($this->routeStore(), $data);

        $response->assertStatus(422)
            ->assertJsonFragment(['Past categories must be related to some past genre']);
    }

    public function testGenreValidation()
    {
        /**
         * @var Category $category
         */
        $category = Category::factory()->create();
        /**
         * @var Genre $genre
         * @var Genre $genre2
         */
        $genre = Genre::factory()->create();
        $genre2 = Genre::factory()->create();

        $genre->categories()->sync([$category->id]);

        $data = [
            'title' => 'asd',
            'description' => 'asdasdasdasdasd',
            'year_launched' => rand(1985, 2022),
            'opened' => true,
            'rating' => Rating::Free,
            'duration' => rand(1, 30),
            'categories' => [$category->id],
            'genres' => [$genre->id, $genre2->id]
        ];

        $response = $this->postJson($this->routeStore(), $data);

        $response->assertStatus(422)
            ->assertJsonFragment(['Past categories must be related to some past genre.']);
    }

    public function testUpdate(): void
    {
        $this->category = Video::factory()->create([
            'title' => 'asd',
            'description' => 'asdasdasdasdasd',
            'year_launched' => rand(1985, 2022),
            'opened' => true,
            'rating' => Rating::Eighteen,
            'duration' => rand(1, 30),
        ]);
        $data = [
            'rating' => Rating::Fourteen,
        ];
        $this->assertUpdate(
            $data,
            $data + ['deleted_at' => null]
        );
    }

    protected function routeStore()
    {
        return route('videos.store');
    }

    protected function routeUpdate()
    {
        return route('videos.update', ['video' => $this->video->id]);
    }

    protected function model()
    {
        return Video::class;
    }
}
