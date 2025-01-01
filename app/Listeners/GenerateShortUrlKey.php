<?php

namespace App\Listeners;

use App\Events\CreatingShortUrl;
use App\Models\ShortUrl;
use Exception;

class GenerateShortUrlKey
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @throws Exception
     */
    public function handle(CreatingShortUrl $event): void
    {
        // Generate a 6 char key
        $exists = true;
        $maxLoop = 10;
        $i = 0;

        while ($exists && $i < $maxLoop) {
            $key = substr(uniqid(), -6);
            $exists = ShortUrl::where('key', $key)->count();
            $i++;
        }

        if ($exists) {
            throw new Exception('Cannot generate unique short key. Maximum tries exceeded.');
        }

        $event->shortUrl->key = $key;
    }
}
