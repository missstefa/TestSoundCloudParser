<?php

namespace Tests\Feature;

use App\Http\Controllers\SoundCloudController;
use App\Models\Member;
use App\Models\Track;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ParseSoundCloudTest extends TestCase
{
    public function route(array $params = []): string
    {
        return action([SoundCloudController::class, 'parseSoundCloud'], $params);
    }

    public function test_it_can_1(): void
    {
        $lakeyinspiredTrackResponse = '{
              "collection": {
                "artwork_url": "https://i1.sndcdn.com/artworks-large.jpg",
                "available_country_codes": null,
                "bpm": null,
                "comment_count": 4,
                "commentable": true,
                "created_at": "2019/01/15 15:40:49 +0000",
                "description": null,
                "download_count": 0,
                "download_url": "https://api.soundcloud.com/tracks/1234/download",
                "downloadable": false,
                "duration": 40000,
                "embeddable_by": "all",
                "favoritings_count": 271,
                "genre": "Rock",
                "id": 1234,
                "isrc": null,
                "key_signature": null,
                "kind": "track",
                "label_name": "some label",
                "license": "all-rights-reserved",
                "permalink_url": "https://soundcloud.com/userPermalink/trackPermalink",
                "playback_count": 8027,
                "purchase_title": null,
                "purchase_url": null,
                "release": null,
                "release_day": 22,
                "release_month": 8,
                "release_year": 2019,
                "reposts_count": 18,
                "secret_uri": null,
                "sharing": "public",
                "stream_url": "https://api.soundcloud.com/tracks/1234/stream",
                "streamable": true,
                "tag_list": "",
                "title": "Some title",
                "uri": "https://api.soundcloud.com/tracks/1234",
                "user": {
                  "avatar_url": "https://i1.sndcdn.com/avatars.jpg",
                  "city": "City",
                  "country": "Country",
                  "created_at": "2018/01/01 12:08:25 +0000",
                  "description": null,
                  "discogs_name": null,
                  "first_name": "First_name",
                  "followers_count": 0,
                  "followings_count": 0,
                  "full_name": "Full Name",
                  "id": 12345,
                  "kind": "user",
                  "last_modified": "2020/01/03 12:08:25 +0000",
                  "last_name": "Last_name",
                  "myspace_name": null,
                  "permalink": "permalink",
                  "permalink_url": "https://soundcloud.com/permalink",
                  "plan": "Free",
                  "playlist_count": 3,
                  "public_favorites_count": 20,
                  "reposts_count": 0,
                  "subscriptions": [
                    {
                      "product": {
                        "id": "some-id",
                        "name": "some.name"
                      }
                    }
                  ],
                  "track_count": 0,
                  "upload_seconds_left": 10800,
                  "uri": "https://api.soundcloud.com/users/1234",
                  "username": "some.user",
                  "website": null,
                  "website_title": null,
                  "$$ref": "#/components/examples/User/value"
                },
                "user_favorite": true,
                "user_playback_count": 1,
                "waveform_url": "https://wave.sndcdn.com/someString.png",
                "access": "playable",
                "$$ref": "#/components/examples/Track/value"
              },
              "next_href": "https://api.soundcloud.com/collection?page_size=10&cursor=1234567"
            }';

        $lakeyinspiredResponse = '{
          "collection": {
            "avatar_url": "https://i1.sndcdn.com/avatars.jpg",
            "city": "City",
            "country": "Country",
            "created_at": "2018/01/01 12:08:25 +0000",
            "description": null,
            "discogs_name": null,
            "first_name": "First_name",
            "followers_count": 0,
            "followings_count": 0,
            "full_name": "Full Name",
            "id": 12345,
            "kind": "user",
            "last_modified": "2020/01/03 12:08:25 +0000",
            "last_name": "Last_name",
            "myspace_name": null,
            "permalink": "permalink",
            "permalink_url": "https://soundcloud.com/permalink",
            "plan": "Free",
            "playlist_count": 3,
            "public_favorites_count": 20,
            "reposts_count": 0,
            "subscriptions": [
              {
                "product": {
                  "id": "some-id",
                  "name": "some.name"
                }
              }
            ],
            "track_count": 0,
            "upload_seconds_left": 10800,
            "uri": "https://api.soundcloud.com/users/1234",
            "username": "some.user",
            "website": null,
            "website_title": null,
            "$$ref": "#/components/examples/User/value"
          },
          "next_href": "https://api.soundcloud.com/collection?page_size=10&cursor=1234567"
        }';

        Http::fake([
            'https://api-v2.soundcloud.com/users/lakeyinspired' => Http::response($lakeyinspiredResponse, 200, [])
        ]);
        Http::fake([
            'https://api-v2.soundcloud.com/tracks/lakeyinspired' => Http::response($lakeyinspiredTrackResponse, 200, [])
        ]);

        $this->postJson($this->route(), ['url' => "https://soundcloud.com/lakeyinspired"])->assertOk();

        $this->assertDatabaseHas(Member::class, [
            'full_name' => 'Full Name',
            'user_name' => 'some.user',
            'city' => 'City',
            'followers_count' => 0
        ]);

        $this->assertDatabaseHas(Track::class, [
            'title' => 'Some title',
            'duration' => 40000,
            'playback_count' => 8027,
            'comment_count' => 4
        ]);
    }

    public function test_for_check_need_token()
    {
        $response = Http::get('https://api-v2.soundcloud.com/tracks');

        $this->assertEquals(401, $response->status());

        $response = Http::get('https://api.soundcloud.com/tracks');

        $this->assertEquals(401, $response->status());
    }

}