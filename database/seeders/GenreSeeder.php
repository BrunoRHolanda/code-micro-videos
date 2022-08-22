<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Genre;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();

        Genre::factory(500)->create()->each(function (Genre $genre) use ($categories) {
            $selectedCategories = $categories->random(5)->pluck('id')->toArray();

            $genre->categories()->attach($selectedCategories);
        });
    }
}
