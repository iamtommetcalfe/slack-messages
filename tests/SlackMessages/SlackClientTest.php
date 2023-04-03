<?php
namespace Tests\SlackMessages;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SlackMessages\Interface\HttpClientInterface;
use SlackMessages\SlackClient;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;
use stdClass;

class SlackClientTest extends TestCase
{
    /**
     * Test the sendMessage method by providing a channel and message text.
     */
    public function testSendMessage(): void
    {
        // Set up the test data
        $channel = 'C12345678';
        $text = 'Hello, World!';
        $responseBody = '{"ok": true}';

        // Create a mock HttpClient
        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) use ($channel, $text) {
                // Assert that the request has the correct method and URI
                $this->assertEquals('POST', $request->getMethod());
                $this->assertEquals('chat.postMessage', $request->getUri());

                // Assert that the request body contains the correct channel and text
                $requestBody = json_decode($request->getBody(), true);
                $this->assertEquals($channel, $requestBody['channel']);
                $this->assertEquals($text, $requestBody['text']);

                return true;
            }))
            ->willReturn(new Response(200, [], $responseBody));

        // Instantiate the SlackClient with the mock HttpClient
        $slackClient = new SlackClient('test-token', $mockHttpClient);

        // Call the sendMessage method and check the result
        $result = $slackClient->sendMessage($channel, $text);

        // Assert that the result is an instance of stdClass and has the 'ok' property set to true
        $this->assertInstanceOf(stdClass::class, $result);
        $this->assertTrue($result->ok);
    }

    /**
     * Test the updateMessage method by providing a channel, message text, and timestamp.
     */
    public function testUpdateMessage(): void
    {
        // Set up the test data
        $channel = 'C12345678';
        $text = 'Updated message text';
        $ts = '1234567890.123456';
        $responseBody = '{"ok": true}';

        // Create a mock HttpClient
        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) use ($channel, $text, $ts) {
                // Assert that the request has the correct method and URI
                $this->assertEquals('POST', $request->getMethod());
                $this->assertEquals('chat.update', $request->getUri());

                // Assert that the request body contains the correct channel, text, and timestamp
                $requestBody = json_decode($request->getBody(), true);
                $this->assertEquals($channel, $requestBody['channel']);
                $this->assertEquals($text, $requestBody['text']);
                $this->assertEquals($ts, $requestBody['ts']);

                return true;
            }))
            ->willReturn(new Response(200, [], $responseBody));

        // Instantiate the SlackClient with the mock HttpClient
        $slackClient = new SlackClient('test-token', $mockHttpClient);

        // Call the updateMessage method and check the result
        $result = $slackClient->updateMessage($channel, $text, $ts);

        // Assert that the result is an instance of stdClass and has the 'ok' property set to true
        $this->assertInstanceOf(stdClass::class, $result);
        $this->assertTrue($result->ok);
    }

    /**
     * Test the sendEphemeralMessage method by providing a channel, user, and message text.
     */
    public function testSendEphemeralMessage(): void
    {
        // Set up the test data
        $channel = 'C12345678';
        $user = 'U12345678';
        $text = 'Ephemeral message text';
        $responseBody = '{"ok": true}';

        // Create a mock HttpClient
        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) use ($channel, $user, $text) {
                // Assert that the request has the correct method and URI
                $this->assertEquals('POST', $request->getMethod());
                $this->assertEquals('chat.postEphemeral', $request->getUri());

                // Assert that the request body contains the correct channel, user, and text
                $requestBody = json_decode($request->getBody(), true);
                $this->assertEquals($channel, $requestBody['channel']);
                $this->assertEquals($user, $requestBody['user']);
                $this->assertEquals($text, $requestBody['text']);

                return true;
            }))
            ->willReturn(new Response(200, [], $responseBody));

        // Instantiate the SlackClient with the mock HttpClient
        $slackClient = new SlackClient('test-token', $mockHttpClient);

        // Call the sendEphemeralMessage method and check the result
        $result = $slackClient->sendEphemeralMessage($channel, $user, $text);

        // Assert that the result is an instance of stdClass and has the 'ok' property set to true
        $this->assertInstanceOf(stdClass::class, $result);
        $this->assertTrue($result->ok);
    }

    /**
     * Test the deleteMessage method by providing a channel and timestamp
     */
    public function testDeleteMessage(): void
    {
        $channel = 'C12345678';
        $timestamp = '1618838594.000200';

        $expectedResponse = json_decode('{
            "ok": true,
            "ts": "1618838594.000200",
            "channel": "C12345678"
        }');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->willReturnCallback(function ($request) use ($expectedResponse) {
                $this->assertSame('POST', $request->getMethod());
                $this->assertSame('chat.delete', $request->getUri()->getPath());
                $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
                $this->assertJsonStringEqualsJsonString(
                    json_encode([
                        'channel' => 'C12345678',
                        'ts' => '1618838594.000200',
                    ]),
                    (string) $request->getBody()
                );
                $response = $this->createMock(ResponseInterface::class);
                $response->method('getBody')->willReturn(json_encode($expectedResponse));
                return $response;
            });

        $slackClient = new SlackClient('test-token', $httpClient);

        $result = $slackClient->deleteMessage($channel, $timestamp);
        $this->assertEquals($expectedResponse, $result);
    }

    /**
     * Test the addReaction method by providing a channel, timestamp and emoji name
     */
    public function testAddReaction()
    {
        // Arrange
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('sendRequest')->willReturn(new Response(200, [], '{"ok": true}'));

        $slackClient = new SlackClient('test-token', $httpClient);

        $channel = 'test-channel';
        $timestamp = '1234567890.123456';
        $emoji = 'thumbsup';

        // Act
        $response = $slackClient->addReaction($channel, $timestamp, $emoji);

        // Assert
        $this->assertInstanceOf(stdClass::class, $response);
        $this->assertTrue($response->ok);
    }

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

        $slackClient = new SlackClient('test-token', $httpClient);

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

        $slackClient = new SlackClient('test-token', $httpClient);

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

        $slackClient = new SlackClient('test-token', $httpClient);

        $result = $slackClient->deleteScheduledMessage($channel, $scheduledMessageId);
        $this->assertEquals($expectedResponse, $result);
    }
}
