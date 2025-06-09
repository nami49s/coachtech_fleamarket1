<?php

namespace Tests\Fakes;

use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use App\Services\ImageServiceInterface;
use Illuminate\Support\Facades\Storage;

class FakeImageService extends ImageService
{
    public function createTrueColor(int $width, int $height)
    {
        return ['width' => $width, 'height' => $height];
    }

    public function createFromJpeg(string $path)
    {
        return 'fake_image';
    }

    public function save($image, string $path)
    {
        return true;
    }

    public function destroy($image)
    {
        return true;
    }

    // 本番で使われる想定のあるsaveImageがある場合
    public function saveImage(UploadedFile $file, string $directory): string
    {
        return $directory . '/fake_saved_image.jpg';
    }

    public function resizeAndSave($image, $path, $width, $height)
    {
        // ダミーのファイル内容を保存（テスト用）
        Storage::put($path, 'fake image content');
    }
}