<?php

namespace App\Actions;

use App\Models\Track;

class ParseTrackAction
{
    public function execute($track)
    {

        Track::query()->updateOrCreate([
            'title' => $track->get('title'),
            'member_id' => $track->get('member_id'),
            'duration' => $track->get('duration'),
            'playback_count' => $track->get('playback_count'),
            'comment_count' => $track->get('comment_count')
        ]);
    }
}