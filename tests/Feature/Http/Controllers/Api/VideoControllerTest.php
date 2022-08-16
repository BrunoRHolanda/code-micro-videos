<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use App\Models\Enums\Rating;
use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestSaves;

class VideoControllerTest extends TestCase
{
    use DatabaseMigrations, TestSaves;

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
        $data = [
            'title' => 'asd',
            'description' => 'asdasdasdasdasd',
            'year_launched' => rand(1985, 2022),
            'opened' => true,
            'rating' => Rating::Free,
            'duration' => rand(1, 30),
        ];
        $this->assertStore(
            $data,
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
        $this->assertStore($data, $data + ['deleted_at' => null]);
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
