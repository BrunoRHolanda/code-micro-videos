<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = Genre::all();

        Video::factory()->count(100)->create()->each(function (Video $video) use ($genres) {
            $selectedGenres = $genres->random(5)->load('categories');
            $categories = [];

            foreach ($selectedGenres as $selectedGenre) {
                array_push($categories, ...$selectedGenre->categories->pluck('id')->toArray());
            }

            $categories = array_unique($categories);

            $video->genres()->attach($selectedGenres->pluck('id')->toArray());
            $video->categories()->attach($categories);
        });
    }
}
