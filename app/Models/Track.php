<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'member_id',
        'duration',
        'playback_count',
        'comment_count'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
