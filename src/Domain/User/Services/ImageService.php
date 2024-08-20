<?php

namespace Domain\User\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    public static function removeImage($imagePath)
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
