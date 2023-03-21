<?php
namespace Tests\SlackMessages;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SlackMessages\Interface\HttpClientInterface;
use SlackMessages\SlackClient;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;

class SlackClientTest extends TestCase
{
    public function testSendMessage(): void
    {
        $channel = 'test-channel';
        $text = 'Test message';

        $response = new Response(200, [], json_encode(['ok' => true]));

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('sendRequest')->willReturn($response);

        $slackClient = new SlackClient($_ENV['SLACK_TEST_TOKEN'], $httpClient);
        $result = $slackClient->sendMessage($channel, $text);

        $this->assertSame('Message sent successfully', $result);
    }

    public function testSendEphemeralMessage()
    {
        $channel = 'C12345678';
        $text = 'This is an ephemeral message';
        $user = 'U12345678';

        $responseJson = json_encode([
            'ok' => true,
            'message_ts' => '123456789.9875',
        ]);

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getBody')->willReturn($responseJson);

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) use ($channel, $text, $user) {
                $requestBody = json_decode($request->getBody(), true);

                return $request->getMethod() === 'POST'
                    && (string) $request->getUri() === 'chat.postEphemeral'
                    && $requestBody['channel'] === $channel
                    && $requestBody['text'] === $text
                    && $requestBody['user'] === $user;
            }))
            ->willReturn($responseMock);

        $slackClient = new SlackClient($_ENV['SLACK_TEST_TOKEN'], $httpClientMock);
        $result = $slackClient->sendEphemeralMessage($channel, $text, $user);

        $this->assertSame('Ephemeral message sent successfully', $result);
    }
}
