<?php

namespace App\Http\Recources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'full_name'         => $this->full_name,
            'user_name'         => $this->user_name,
            'user_id'           => $this->user_id,
            'city'              => $this->city,
            'followers_count'   => $this->followers_count,
            'tracks'            => TrackResource::collection($this->tracks),

        ];
    }
}