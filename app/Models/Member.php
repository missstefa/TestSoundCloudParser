<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'user_name',
        'user_id',
        'city',
        'followers_count'
    ];

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class);
    }
}
