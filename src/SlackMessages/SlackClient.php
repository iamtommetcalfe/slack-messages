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
        // Prepare the request data and create a new Request object.
        $requestData = [
            'channel' => $channelId,
            'text' => $text,
        ];

        $request = new Request(
            'POST',
            'chat.postMessage',
            [
                'Authorization' => "Bearer {$this->slackToken}",
                'Content-Type' => 'application/json',
            ],
            json_encode($requestData)
        );

        // Send the request and handle the response.
        try {
            $response = $this->httpClient->sendRequest($request);

            return $this->processResponse($response);
        } catch (GuzzleException|ClientExceptionInterface $e) {
            throw new InvalidArgumentException('Error: ' . $e->getMessage());
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
        $requestData = [
            'channel' => $channelId,
            'text' => $text,
            'ts' => $ts,
        ];

        $request = new Request(
            'POST',
            'chat.update',
            [
                'Authorization' => "Bearer {$this->slackToken}",
                'Content-Type' => 'application/json',
            ],
            json_encode($requestData)
        );

        try {
            $response = $this->httpClient->sendRequest($request);
            return $this->processResponse($response);
        } catch (GuzzleException|ClientExceptionInterface $e) {
            throw new InvalidArgumentException('Error: ' . $e->getMessage());
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
        $requestData = [
            'channel' => $channelId,
            'text' => $text,
            'user' => $user,
        ];

        $request = new Request(
            'POST',
            'chat.postEphemeral',
            [
                'Authorization' => "Bearer {$this->slackToken}",
                'Content-Type' => 'application/json',
            ],
            json_encode($requestData)
        );

        try {
            $response = $this->httpClient->sendRequest($request);

            return $this->processResponse($response);
        } catch (GuzzleException|ClientExceptionInterface $e) {
            throw new InvalidArgumentException('Error: ' . $e->getMessage());
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
        $requestData = [
            'channel' => $channel,
            'ts' => $timestamp,
        ];

        $request = new Request(
            'POST',
            'chat.delete',
            [
                'Authorization' => "Bearer {$this->slackToken}",
                'Content-Type' => 'application/json',
            ],
            json_encode($requestData)
        );

        try {
            $response = $this->httpClient->sendRequest($request);
            return $this->processResponse($response);
        } catch (GuzzleException|ClientExceptionInterface $e) {
            throw new InvalidArgumentException('Error: ' . $e->getMessage());
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
}
