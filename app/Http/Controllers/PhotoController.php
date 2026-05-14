<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Events\PhotoLiked;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function index()
    {
        $photos = [
            (object)[
                'id' => 1,
                'title' => 'Mountain View',
                'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb',
                'likes' => 0
            ],

            (object)[
                'id' => 2,
                'title' => 'Beach Sunset',
                'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e',
                'likes' => 0
            ],

            (object)[
                'id' => 3,
                'title' => 'City Night',
                'image' => 'https://images.unsplash.com/photo-1519501025264-65ba15a82390',
                'likes' => 0
            ]
        ];

        return view('welcome', compact('photos'));
    }

    public function like($id)
    {
        $photo = (object)[
            'id' => $id,
            'likes' => rand(1, 999)
        ];

        broadcast(new PhotoLiked($photo))->toOthers();

        return response()->json([
            'success' => true
        ]);
    }
}