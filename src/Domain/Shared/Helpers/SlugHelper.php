<?php

namespace Domain\Shared\Helpers;

use Illuminate\Support\Str;

class SlugHelper
{
    public static function generateUniqueSlug(?string $stringToBeSlugified, string $model, ?int $id = null, string $slugFieldName = 'slug', ?string $slug = null): string
    {
        // Generating 1st attempt of slug
        $slug = $slug ?: ($stringToBeSlugified ? Str::slug($stringToBeSlugified) : '');

        // Check if the slug is already taken
        $countQueryBuilder = $model::where($slugFieldName, $slug);

        if ($id) {
            $countQueryBuilder->where('id', '!=', $id);
        }

        $count = $countQueryBuilder->count();

        if ($count > 0) {
            // Checking if ends with number.
            $extractNumberFromEndResult = StringHelper::extractNumberFromEnd($slug);

            // If ends with number, we add +1, else we add 2 on the end.
            $newSlug = $extractNumberFromEndResult['result'] ? $extractNumberFromEndResult['base'].((int) $extractNumberFromEndResult['index'] + 1) : $slug.'2';

            return self::generateUniqueSlug(null, $model, $id, $slugFieldName, $newSlug);
        }

        return $slug;
    }
}
