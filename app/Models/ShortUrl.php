<?php

namespace App\Models;

use App\Events\CreatingShortUrl;
use Illuminate\Database\Eloquent\Model;

/**
* @property string $key;
* @property string $url;
*/
class ShortUrl extends Model
{
    protected $fillable = ['url'];

    protected $dispatchesEvents = [
        'creating' => CreatingShortUrl::class,
    ];
}
