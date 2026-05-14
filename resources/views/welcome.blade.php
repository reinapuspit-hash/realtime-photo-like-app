<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Realtime Photo Like App</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 min-h-screen text-white">

@php
$photos = [
    (object)[
        'id' => 1,
        'title' => 'Mountain View',
        'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=1000',
        'likes' => 0
    ],

    (object)[
        'id' => 2,
        'title' => 'Beach Sunset',
        'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1000',
        'likes' => 0
    ],

    (object)[
        'id' => 3,
        'title' => 'City Night',
        'image' => 'https://images.unsplash.com/photo-1519501025264-65ba15a82390?w=1000',
        'likes' => 0
    ]
];
@endphp

<div class="container mx-auto px-6 py-10">

    <div class="text-center mb-14">
        <h1 class="text-6xl font-extrabold mb-4">
            PixelWave Gallery
        </h1>

        <p class="text-xl text-slate-300">
            Share moments. Feel the vibe.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

        @foreach($photos as $photo)

        <div class="bg-slate-800 rounded-3xl overflow-hidden shadow-2xl hover:scale-105 transition duration-300">

            <div class="relative">
                <img
                    src="{{ $photo->image }}"
                    class="w-full h-80 object-cover"
                >

                <div class="absolute top-4 right-4 bg-black/50 px-4 py-2 rounded-full text-sm">
                    🔥 Trending
                </div>
            </div>

            <div class="p-6">

                <div class="flex justify-between items-center mb-5">

                    <div>
                        <h2 class="text-4xl font-bold">
                            {{ $photo->title }}
                        </h2>

                        <p class="text-slate-400">
                            Uploaded just now
                        </p>
                    </div>

                    <div class="text-center">
                        <p class="text-slate-300 text-sm">
                            ❤️ Loved By People
                        </p>

                        <h1
                            id="likes-{{ $photo->id }}"
                            class="text-5xl font-extrabold text-pink-500"
                        >
                            {{ $photo->likes }}
                        </h1>
                    </div>

                </div>

                <button
                    onclick="likePhoto({{ $photo->id }})"
                    class="w-full bg-pink-500 hover:bg-pink-600 transition duration-300 py-4 rounded-2xl text-2xl font-bold shadow-lg"
                >
                    ❤️ Like Photo
                </button>

            </div>

        </div>

        @endforeach

    </div>

</div>

<script>
    function likePhoto(id) {

        let likeElement =
            document.getElementById(`likes-${id}`);

        let currentLikes =
            parseInt(likeElement.innerText);

        let updatedLikes =
            currentLikes + 1;

        likeElement.innerText = updatedLikes;

        window.Echo.channel('photo-channel')
            .listen('.photo.liked', (event) => {

                if(event.photo.id == id) {

                    likeElement.innerText =
                        event.photo.likes;
                }
            });
    }
</script>

</body>
</html>