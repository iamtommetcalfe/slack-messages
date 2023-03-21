<?php
namespace SlackMessages\Factory;

use SlackMessages\HttpClient;
use SlackMessages\Interface\HttpClientInterface;

/**
 * HttpClientFactory class for creating instances of HttpClient.
 */
class HttpClientFactory
{
    /**
     * Creates an HttpClient instance with the specified configuration.
     *
     * @param array $config An optional array of configuration options. This may include 'base_uri' and 'headers'.
     * @return HttpClientInterface The created HttpClient instance.
     */
    public function create(array $config = []): HttpClientInterface
    {
        // Retrieve the 'base_uri' and 'headers' values from the provided configuration, if available.
        $baseUri = $config['base_uri'] ?? null;
        $headers = $config['headers'] ?? [];

        // Initialize an empty array to store the final Guzzle configuration.
        $guzzleConfig = [];

        // Set the 'base_uri' in the Guzzle configuration if it was provided.
        if ($baseUri) {
            $guzzleConfig['base_uri'] = $baseUri;
        }

        // Set the 'headers' in the Guzzle configuration if any were provided.
        if (!empty($headers)) {
            $guzzleConfig['headers'] = $headers;
        }

        // Create a new HttpClient instance with the assembled Guzzle configuration.
        return new HttpClient($guzzleConfig);
    }
}
