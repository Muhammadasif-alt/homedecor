<?php

namespace App\Services;

use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\UriInterface;
use RuntimeException;

/**
 * Downloads an image that was pasted into the admin as a web address (copied from a WordPress
 * post, Google Docs or an AI writing tool) to a temporary file, so ImageOptimizer can store it.
 * Only public http(s) hosts are fetched, redirects included, and anything over 10 MB or not an
 * image is refused.
 */
class RemoteImageFetcher
{
    public const MAX_BYTES = 10 * 1024 * 1024;

    /** @return string Path of a temporary file holding the image; the caller deletes it. */
    public function fetch(string $url): string
    {
        $this->assertPublic($url);

        try {
            $response = Http::timeout(20)
                ->withHeaders(['Accept' => 'image/webp,image/png,image/jpeg,image/*'])
                ->withOptions([
                    'allow_redirects' => [
                        'max' => 3,
                        'protocols' => ['http', 'https'],
                        'on_redirect' => fn ($request, $response, UriInterface $uri) => $this->assertPublic((string) $uri),
                    ],
                ])
                ->get($url);
        } catch (HttpClientException $e) {
            report($e);

            throw new RuntimeException('The image could not be downloaded from that address. Save it and upload it on the next step.');
        }

        if (! $response->successful()) {
            throw new RuntimeException("The image address answered {$response->status()}.");
        }

        $body = $response->body();
        if ($body === '' || strlen($body) > self::MAX_BYTES) {
            throw new RuntimeException('The image is empty or larger than 10 MB.');
        }
        if (! str_starts_with(strtolower((string) $response->header('Content-Type')), 'image/') && @getimagesizefromstring($body) === false) {
            throw new RuntimeException('That address is not an image.');
        }

        $path = tempnam(sys_get_temp_dir(), 'paste');
        file_put_contents($path, $body);

        return $path;
    }

    private function assertPublic(string $url): void
    {
        $parts = parse_url($url);
        $host = $parts['host'] ?? '';
        if (! in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true) || $host === '') {
            throw new RuntimeException('Only http and https image addresses can be imported.');
        }

        $ips = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : (gethostbynamel($host) ?: []);
        if ($ips === []) {
            throw new RuntimeException("The image host {$host} could not be found.");
        }
        foreach ($ips as $ip) {
            if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new RuntimeException('Images on private or local addresses cannot be imported.');
            }
        }
    }
}
