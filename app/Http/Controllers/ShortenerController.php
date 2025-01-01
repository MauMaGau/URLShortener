<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShortenerController extends Controller
{
    // decode a short url key into a long url
    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shorturl' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['message' => 'shorturl is required'], 402);
        }

        // Get the key from the supplied url. To allow either the key or full url to be entered,
        // we take only the last segment of the url (everything after the last forward slash).
        $shortUrlString = $request->get('shorturl');
        $splitUrl = explode('/', $shortUrlString);
        $shortUrlString = $splitUrl[array_key_last($splitUrl)];

        $shortUrl = ShortUrl::where('key', $shortUrlString)->get();

        if ($shortUrl->isEmpty()) {
            return response(['message' => $shortUrlString . ' cannot be found'], 404);
        }

        if (count($shortUrl) > 1) {
            return response(['message' => 'More than one url found for this short url'], 500);
        }

        return response([
            'long-url' => $shortUrl->first()->url,
        ]);
    }

    public function create()
    {
        return view('shortener.create');
    }

    // encode a long url into a short one
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'longurl' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->messages()], 402);
        }

        $shortUrl = ShortUrl::create([
            'url' => $request->get('longurl')
        ]);

        return response([
            'domain' => env('SHORTENER_DOMAIN'),
            'key' => $shortUrl->key,
            'short-url' => env('SHORTENER_DOMAIN') . '/' . $shortUrl->key,
        ]);
    }
}
