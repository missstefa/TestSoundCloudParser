<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParseSoundCloudRequest extends FormRequest
{
    public function rules(): array
    {
        return  [
            'url' => ["required", "string"],
        ];
    }

    public function getUrl()
    {
        return $this->get('url');
    }
}