<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!File::exists(storage_path('app/public/item_images'))) {
            File::makeDirectory(storage_path('app/public/item_images'), 0755, true);
        }

        if (!File::exists(storage_path('app/public/profile_images'))) {
            File::makeDirectory(storage_path('app/public/profile_images'), 0755, true);
        }

        // item画像コピー
        $itemImages = [
            'ArmaniMensClock.jpg',
            'HDDHardDisk.jpg',
            'iLoveIMGd.jpg',
            'LeatherShoesProductPhoto.jpg',
            'LivingRoomLaptop.jpg',
            'MusicMic4632231.jpg',
            'Pursefashionpocket.jpg',
            'Tumblersouvenir.jpg',
            'WaitresswithCoffeeGrinder.jpg',
            'makeset.jpg',
            'sample.jpg',
        ];

        foreach ($itemImages as $image) {
            File::copy(
                database_path('seeders/images/' . $image),
                storage_path('app/public/item_images/' . $image)
            );
        }

        // profile画像コピー
        File::copy(
            database_path('seeders/images/profile.jpg'),
            storage_path('app/public/profile_images/profile.jpg')
        );

        $this->call([
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            ItemsTableSeeder::class,
        ]);
    }
}
