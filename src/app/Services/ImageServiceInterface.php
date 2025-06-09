<?php

namespace App\Services;

interface ImageServiceInterface
{
    public function resizeAndSave($image, $path, $width, $height);
}