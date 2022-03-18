<?php

namespace App\Actions;

use App\Models\Member;

class ParseMemberAction
{
    public function execute(array $member): Member
    {
        $memberCollection = collect($member['collection']);

        $member= Member::query()->updateOrCreate([
            'full_name' => $memberCollection->get('full_name'),
            'user_name' => $memberCollection->get('username'),
            'user_id' => $memberCollection->get('id'),
            'city' => $memberCollection->get('city'),
            'followers_count' => $memberCollection->get('followers_count')
        ]);

        return $member;
    }

}