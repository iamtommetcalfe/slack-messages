<?php
namespace Tests\SlackMessages;

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
}
