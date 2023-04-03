<?php

namespace Tests\SlackMessages;

use Psr\Http\Message\ResponseInterface;
use SlackMessages\Interface\HttpClientInterface;
use SlackMessages\ScheduledMessage;
use PHPUnit\Framework\TestCase;

class ScheduledMessageTest extends TestCase
{
    /**
     * Test the scheduleMessage method by providing a channel, message text, and postAt timestamp.
     */
    public function testScheduleMessage(): void
    {
        $channel = 'C12345678';
        $text = 'Scheduled message text';
        $postAt = 1672845600;

        $expectedResponse = json_decode('{
            "ok": true,
            "scheduled_message_id": "1234567890.123456",
            "post_at": 1672845600,
            "channel": "C12345678"
        }');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->willReturnCallback(function ($request) use ($expectedResponse) {
                $this->assertSame('POST', $request->getMethod());
                $this->assertSame('chat.scheduleMessage', $request->getUri()->getPath());
                $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
                $this->assertJsonStringEqualsJsonString(
                    json_encode([
                        'channel' => 'C12345678',
                        'text' => 'Scheduled message text',
                        'post_at' => 1672845600,
                    ]),
                    (string) $request->getBody()
                );
                $response = $this->createMock(ResponseInterface::class);
                $response->method('getBody')->willReturn(json_encode($expectedResponse));
                return $response;
            });

        $slackClient = new ScheduledMessage('test-token', $httpClient);

        $result = $slackClient->scheduleMessage($channel, $text, $postAt);
        $this->assertEquals($expectedResponse, $result);
    }

    /**
     * Test the listScheduledMessages method by providing a channel.
     */
    public function testListScheduledMessages(): void
    {
        $channel = 'C12345678';

        $expectedResponse = json_decode('{
            "ok": true,
            "scheduled_messages": [
                {
                    "id": "1234567890.123456",
                    "channel_id": "C12345678",
                    "post_at": 1672845600,
                    "date_created": 1669875600,
                    "text": "Scheduled message text"
                }
            ],
            "response_metadata": {
                "next_cursor": ""
            }
        }');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->willReturnCallback(function ($request) use ($expectedResponse) {
                $this->assertSame('GET', $request->getMethod());
                $this->assertSame('chat.scheduledMessages.list', $request->getUri()->getPath());
                $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
                $response = $this->createMock(ResponseInterface::class);
                $response->method('getBody')->willReturn(json_encode($expectedResponse));
                return $response;
            });

        $slackClient = new ScheduledMessage('test-token', $httpClient);

        $result = $slackClient->listScheduledMessages($channel);
        $this->assertEquals($expectedResponse, $result);
    }

    /**
     * Test the deleteScheduledMessage method by providing a channel and scheduledMessageId.
     */
    public function testDeleteScheduledMessage(): void
    {
        $channel = 'C12345678';
        $scheduledMessageId = '1234567890.123456';

        $expectedResponse = json_decode('{
            "ok": true,
            "scheduled_message_id": "1234567890.123456",
            "channel": "C12345678"
        }');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->willReturnCallback(function ($request) use ($expectedResponse) {
                $this->assertSame('POST', $request->getMethod());
                $this->assertSame('chat.deleteScheduledMessage', $request->getUri()->getPath());
                $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
                $this->assertJsonStringEqualsJsonString(
                    json_encode([
                        'channel' => 'C12345678',
                        'scheduled_message_id' => '1234567890.123456',
                    ]),
                    (string) $request->getBody()
                );
                $response = $this->createMock(ResponseInterface::class);
                $response->method('getBody')->willReturn(json_encode($expectedResponse));
                return $response;
            });

        $slackClient = new ScheduledMessage('test-token', $httpClient);

        $result = $slackClient->deleteScheduledMessage($channel, $scheduledMessageId);
        $this->assertEquals($expectedResponse, $result);
    }
}
