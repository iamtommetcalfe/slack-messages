<?php
namespace SlackMessages;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use SlackMessages\Factory\HttpClientFactory;
use SlackMessages\Interface\HttpClientInterface;
use SlackMessages\Interface\MessageSenderInterface;

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
     * @param string $channel The channel to send the message to.
     * @param string $text The message text.
     * @return string A string describing the success or failure of the message sending.
     */
    public function sendMessage(string $channel, string $text): string
    {
        // Prepare the request data and create a new Request object.
        $requestData = [
            'channel' => $channel,
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

            $responseData = json_decode($response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidArgumentException('Error decoding response JSON: ' . json_last_error_msg());
            }

            if ($responseData['ok']) {
                return 'Message sent successfully';
            } else {
                return 'Error: ' . $responseData['error'];
            }
        } catch (GuzzleException|ClientExceptionInterface $e) {
            return 'Error: ' . $e->getMessage();
        } catch (InvalidArgumentException $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
