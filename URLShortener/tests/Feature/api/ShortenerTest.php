<?php

namespace Tests\Feature\api;

use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortenerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_short_url_from_a_long_url(): void
    {
        $response = $this->post(route('shortener.store'), ['longurl' => 'https://example.com']);

        $response->assertStatus(200);
        $response->assertJsonStructure(['short-url']);
        $this->assertEquals(6, strlen($response->json('key')));
        $this->assertStringEndsWith($response->json('key'), $response->json('short-url'));
    }

    public function test_it_requires_a_long_url(): void
    {
        $response = $this->post(route('shortener.store'), []);

        $response->assertStatus(402);
        $response->assertJsonStructure(['errors']);
    }

    public function test_it_gets_a_long_url_from_a_short_url(): void
    {
        $shortUrl = ShortUrl::create([
            'url' => 'https://example.com',
        ]);

        $response = $this->get(route('shortener.show', ['shorturl' => env('SHORTENER_DOMAIN') . '/' . $shortUrl->key]));

        $response->assertStatus(200);
        $response->assertJsonStructure(['long-url']);
        $response->json();
    }

    public function test_it_gets_a_long_url_from_a_short_url_key(): void
    {
        $shortUrl = ShortUrl::create([
            'url' => 'https://example.com',
        ]);

        $response = $this->get(route('shortener.show', ['shorturl' => $shortUrl->key]));

        $response->assertStatus(200);
        $response->assertJsonStructure(['long-url']);
    }

    public function test_it_encodes_and_decodes_a_url(): void
    {
        $longUrl = 'https://example.com/wibble?param=foo';

        $response = $this->post(route('shortener.store'), ['longurl' => $longUrl]);

        $shortUrl = $response->json('short-url');

        $response = $this->get(route('shortener.show', ['shorturl' => env('SHORTENER_DOMAIN') . '/' . $shortUrl]));

        $response->assertStatus(200);
        $response->assertExactJson([
           'long-url' => $longUrl,
        ]);
    }
}
