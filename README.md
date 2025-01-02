See /tests/Feature/api/ShortenerTest.php for tests - they should give a good idea of the api. 

Endpoints are /api/encode and /api/decode.

If you load this project up in a dev environment (runs with Laravel Sail), you can also use the /shortener/create page to create a short url.

See App/Http/Controllers/ShortenerController, App/Models/ShortUrl, and App/Listeners/GenerateShortUrlKey for logic.

When a longurl is POSTED to the /encode endpoint, a ShortUrl model is created. A model event is fired at this point to create a unique 6 character key, which is stored alongside the original URL in the ShortUrls table.A JSON array containing the short URL, as well as it's constituent parts, is returned.

Storing just the key, rather than a full short URL, means the ShortUrl domain name (currently defined in the .env file) can be changed at a later date without the need for any database changes.

To decide, either the full short URL, or just the key, can be supplied as a GET parameter (?ShortUrl=xyz) to the /decode endpoint. This simple pulls the associated long URL from the database and returns it as JSON.