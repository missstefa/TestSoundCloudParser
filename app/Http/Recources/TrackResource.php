<?php

namespace App\Http\Recources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrackResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'duration'          => $this->duration,
            'playback_count'    => $this->playback_count,
            'comment_count'     => $this->comment_count,
        ];
    }
}