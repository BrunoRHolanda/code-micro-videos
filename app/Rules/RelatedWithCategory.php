<?php

namespace App\Rules;

use App\Models\Genre;
use Illuminate\Contracts\Validation\Rule;
use Request;

class RelatedWithCategory implements Rule
{

    public function passes($attribute, $value): bool
    {
        $categories = Request::input('categories');

        if (!$categories) {
            return false;
        }

        foreach ($value as $genreId) {
            /**
             * @var Genre $genre
             */
            $genre = Genre::find($genreId);

            $numberOfCategoriesRelated =
                $genre
                    ->categories()
                    ->whereIn('id', $categories)
                    ->count();
            if ($numberOfCategoriesRelated == 0) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        return 'Past categories must be related to some past genre.';
    }
}
