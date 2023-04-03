<?php

namespace SlackMessages;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use SlackMessages\Factory\HttpClientFactory;
use SlackMessages\Interface\HttpClientInterface;
use stdClass;
use Throwable;

/**
 * SlackClient class for sending messages to Slack channels.
 * Implements the MessageSenderInterface.
 */
abstract class AbstractSlackClient
{
    private const BASE_URI = 'https://slack.com/api/';

    /**
     * @var HttpClientInterface The HTTP client for making requests to the Slack API.
     */
    private HttpClientInterface $httpClient;

    /**
     * @var string The Slack API token for authentication.
     */
    private string $slackToken;

    /**
     * SlackClient constructor.
     *
     * @param string $slackToken The Slack API token.
     * @param HttpClientInterface|null $httpClient (Optional) The HTTP client to use for making requests.
     */
    public function __construct(string $slackToken, HttpClientInterface $httpClient = null)
    {
        $this->slackToken = $slackToken;
        $httpClientFactory = new HttpClientFactory();
        $this->httpClient = $httpClient ?? $httpClientFactory->create([
            'base_uri' => self::BASE_URI,
            'headers' => [
                'Authorization' => "Bearer {$this->slackToken}",
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Processes the response from the Slack API and returns the JSON object.
     *
     * @param ResponseInterface $response The API response.
     * @return stdClass The JSON object representation of the response.
     * @throws InvalidArgumentException If there is an error decoding the response JSON.
     */
    private function processResponse(ResponseInterface $response): stdClass
    {
        $responseData = json_decode($response->getBody());

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Error decoding response JSON: ' . json_last_error_msg());
        }

        return $responseData;
    }

    /**
     * Create a request object with the common headers and given method, endpoint, and payload.
     *
     * @param string $method The HTTP method for the request (e.g., 'POST', 'GET', etc.).
     * @param string $endpoint The Slack API endpoint for the request (e.g., 'chat.postMessage').
     * @param array $payload The request payload containing the required data for the API call.
     *
     * @return Request The prepared request object.
     */
    protected function createRequest(string $method, string $endpoint, array $payload): Request
    {
        return new Request(
            $method,
            $endpoint,
            [
                'Authorization' => "Bearer {$this->slackToken}",
                'Content-Type' => 'application/json',
            ],
            json_encode($payload)
        );
    }

    /**
     * Executes a request.
     *
     * @param Request $request
     * @return stdClass
     */
    protected function executeRequest(Request $request): stdClass
    {
        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (GuzzleException | ClientExceptionInterface $e) {
            $this->handleException($e);
        }

        return $this->processResponse($response);
    }


    /**
     * @param Throwable $e
     * @return void
     */
    private function handleException(Throwable $e): void
    {
        if ($e instanceof ClientExceptionInterface) {
            throw new InvalidArgumentException('HTTP request error: ' . $e->getMessage());
        } else {
            throw new InvalidArgumentException('Unknown error: ' . $e->getMessage());
        }
    }
}
