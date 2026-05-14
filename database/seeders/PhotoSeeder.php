<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Photo;

class PhotoSeeder extends Seeder
{
    public function run(): void
    {
        Photo::create([
            'title' => 'Beautiful Sunset',
            'image' => 'https://picsum.photos/600/400',
            'likes' => 0,
        ]);

        Photo::create([
            'title' => 'Nature View',
            'image' => 'https://picsum.photos/600/401',
            'likes' => 0,
        ]);
    }
}