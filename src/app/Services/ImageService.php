<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService implements ImageServiceInterface
{
    public function resizeAndSave($image, $path, $width, $height)
    {
        // GDなし環境向け、単に画像ファイルを保存だけする
        // 例えば、$image は UploadedFile インスタンス想定
        Storage::putFileAs('', $image, $path);
    }
}