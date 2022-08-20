<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Request;

class RelatedWithGenre implements Rule
{
    public function passes($attribute, $value): bool
    {
        $genres = Request::input('genres');

        if (!$genres) {
            return false;
        }

        foreach ($value as $categoryId) {
            /**
             * @var Category $category
             */
            $category = Category::find($categoryId);

            $numberOfGenresRelated =
                $category
                    ->genres()
                    ->whereIn('id', $genres)
                    ->count();

            if ($numberOfGenresRelated == 0) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        return 'Past categories must be related to some past genre';
    }
}
