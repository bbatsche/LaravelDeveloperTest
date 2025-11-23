<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use JsonException;
use RuntimeException;

final class PlaceholderApiService implements PlaceholderApiInterface
{
    public function __construct(
        private Client $client
    ) {}

    public function getUsers(): array
    {
        return $this->sendRequest('/users');
    }

    public function getPosts(): array
    {
        return $this->sendRequest('/posts');
    }

    private function sendRequest(string $endpoint): array
    {
        try {
            $response = $this->client->get($endpoint);
        } catch (BadResponseException $e) {
            // If a more severe exception is thrown we'll let it bubble up
            throw new RuntimeException(
                "Error sending request for {$endpoint}",
                $e->getResponse()->getStatusCode(),
                $e,
            );
        }

        $body = $response->getBody()->getContents();

        try {
            return json_decode($body, flags: JSON_THROW_ON_ERROR | JSON_OBJECT_AS_ARRAY);
        } catch (JsonException $e) {
            throw new RuntimeException('Could not parse response body as JSON', previous: $e);
        }
    }
}
