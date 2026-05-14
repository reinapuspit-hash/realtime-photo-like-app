<?php

namespace App\Events;

use App\Models\Photo;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PhotoLiked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $photo;

    public function __construct(Photo $photo)
    {
        $this->photo = $photo;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('photo-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'photo.liked';
    }
}