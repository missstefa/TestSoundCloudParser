<?php

namespace Tests\Feature;

use App\Http\Controllers\SoundCloudController;
use Tests\TestCase;

class ParseSoundCloudTest extends TestCase
{
    public function route(array $params = []): string
    {
        return action([SoundCloudController::class, 'parseSoundCloud'], $params);
    }

    public function test_it_can(): void
    {
        $this->postJson($this->route(), ['url' =>  "https://soundcloud.com/lakeyinspired"])->dd();
    }

}