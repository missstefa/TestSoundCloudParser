<?php

namespace App\Http\Controllers;

use App\Actions\ParseMemberAction;
use App\Actions\ParseTrackAction;
use App\Http\Recources\MemberResource;
use App\Http\Requests\ParseSoundCloudRequest;
use App\Models\Member;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Http;

class SoundCloudController extends Controller
{
    public function __construct(private ParseMemberAction $memberAction, private ParseTrackAction $trackAction)
    {
    }

    public function parseSoundCloud(ParseSoundCloudRequest $request): MemberResource
    {
        $userName = basename($request->getUrl());

        $memberResponse = Http::get("https://api-v2.soundcloud.com/users/$userName");
        $member = $this->memberAction->execute($memberResponse->json());

        $trackResponse = Http::get("https://api-v2.soundcloud.com/tracks/$userName");
        $trackCollection = collect($trackResponse->json()['collection']);

        if ($member->user_id != $trackCollection->get('user')['id']) {
            throw new \Exception('This user does not have any tracks');
        }

        $trackCollection->put('member_id', $member->id);

        $this->trackAction->execute($trackCollection);

        $member->refresh();

        return new MemberResource($member);
    }
}
