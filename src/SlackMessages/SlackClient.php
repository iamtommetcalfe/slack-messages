<?php

namespace SlackMessages;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use SlackMessages\Factory\HttpClientFactory;
use SlackMessages\Interface\HttpClientInterface;
use SlackMessages\Interface\MessageSenderInterface;
use stdClass;
use Throwable;

/**
 * SlackClient class for sending messages to Slack channels.
 * Implements the MessageSenderInterface.
 */
class SlackClient implements MessageSenderInterface
{
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
            'base_uri' => 'https://slack.com/api/',
            'headers' => [
                'Authorization' => "Bearer {$this->slackToken}",
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Sends a message to a Slack channel.
     *
     * @param string $channelId The channel ID to send the message to.
     * @param string $text The message text.
     * @return stdClass A stdClass containing the entire response from the Slack API
     */
    public function sendMessage(string $channelId, string $text): stdClass
    {
        $request = $this->createRequest('POST', 'chat.postMessage', [
            'channel' => $channelId,
            'text' => $text,
        ]);

        // Send the request and handle the response.
        try {
            $response = $this->httpClient->sendRequest($request);

            return $this->processResponse($response);
        } catch (GuzzleException | ClientExceptionInterface $e) {
            $this->handleException($e);
        }
    }

    /**
     * Updates an existing message in a Slack channel.
     *
     * @param string $channelId The channel ID containing the message to update.
     * @param string $text The updated message text.
     * @param string $ts The timestamp of the message to update.
     * @return stdClass A stdClass containing the entire response from the Slack API
     */
    public function updateMessage(string $channelId, string $text, string $ts): stdClass
    {
        $request = $this->createRequest('POST', 'chat.update', [
            'channel' => $channelId,
            'text' => $text,
            'ts' => $ts,
        ]);

        try {
            $response = $this->httpClient->sendRequest($request);
            return $this->processResponse($response);
        } catch (GuzzleException | ClientExceptionInterface $e) {
            $this->handleException($e);
        }
    }

    /**
     * Sends an ephemeral message to a Slack channel, visible only to a specific user.
     *
     * @param string $channelId The channel ID to send the message to.
     * @param string $user The ID of the user who will see the ephemeral message.
     * @param string $text The message text.
     * @return stdClass A stdClass containing the entire response from the Slack API
     */
    public function sendEphemeralMessage(string $channelId, string $user, string $text): stdClass
    {
        $request = $this->createRequest('POST', 'chat.postEphemeral', [
            'channel' => $channelId,
            'text' => $text,
            'user' => $user,
        ]);

        try {
            $response = $this->httpClient->sendRequest($request);

            return $this->processResponse($response);
        } catch (GuzzleException | ClientExceptionInterface $e) {
            $this->handleException($e);
        }
    }

    /**
     * Deletes a message in a Slack channel.
     *
     * @param string $channel The channel where the message is located.
     * @param string $timestamp The timestamp of the message to delete.
     * @return stdClass The JSON object representing the API response.
     */
    public function deleteMessage(string $channel, string $timestamp): stdClass
    {
        $request = $this->createRequest('POST', 'chat.delete', [
            'channel' => $channel,
            'ts' => $timestamp,
        ]);

        try {
            $response = $this->httpClient->sendRequest($request);
            return $this->processResponse($response);
        } catch (GuzzleException | ClientExceptionInterface $e) {
            $this->handleException($e);
        }
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
     * Add an emoji reaction to a message in a Slack channel.
     *
     * @param string $channel The channel ID where the message is located.
     * @param string $timestamp The timestamp of the message to add the reaction to.
     * @param string $emoji The emoji to be added as a reaction (e.g., 'thumbsup').
     *
     * @return stdClass The JSON decoded response from the Slack API.
     */
    public function addReaction(string $channel, string $timestamp, string $emoji): stdClass
    {
        $request = $this->createRequest('POST', 'reactions.add', [
            'name' => $emoji,
            'channel' => $channel,
            'timestamp' => $timestamp,
        ]);

        try {
            $response = $this->httpClient->sendRequest($request);
            return $this->processResponse($response);
        } catch (GuzzleException | ClientExceptionInterface $e) {
            $this->handleException($e);
        }
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
    private function createRequest(string $method, string $endpoint, array $payload): Request
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
     * @param Throwable $e
     * @return void
     */
    private function handleException(Throwable $e): void
    {
        throw new InvalidArgumentException('Error: ' . $e->getMessage());
    }
}
