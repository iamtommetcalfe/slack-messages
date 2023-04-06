<?php

namespace Tests\SlackMessages;

use GuzzleHttp\Handler\MockHandler;
use Psr\Http\Message\ResponseInterface;
use SlackMessages\Interface\HttpClientInterface;
use SlackMessages\ReactionOperations;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;
use stdClass;

class ReactionOperationsTest extends TestCase
{
    /**
     * Test the addReaction method by providing a channel, timestamp and emoji name
     */
    public function testAddReaction()
    {
        // Arrange
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('sendRequest')->willReturn(new Response(200, [], '{"ok": true}'));

        $slackClient = new ReactionOperations('test-token', $httpClient);

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
     * Test the deleteReaction method by providing a channel, timestamp and emoji name
     */
    public function testDeleteReaction()
    {
        // Arrange
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('sendRequest')->willReturn(new Response(200, [], '{"ok": true}'));

        $slackClient = new ReactionOperations('test-token', $httpClient);

        $channel = 'test-channel';
        $timestamp = '1234567890.123456';
        $emoji = 'thumbsup';

        // Act
        $response = $slackClient->deleteReaction($channel, $timestamp, $emoji);

        // Assert
        $this->assertInstanceOf(stdClass::class, $response);
        $this->assertTrue($response->ok);
    }

    /**
     * Test the getReactions method by providing a channel and timestamp
     */
    public function testGetReactions()
    {
        $channel = 'C12345678';
        $timestamp = '1679580403.818139';

        $expectedResponse = json_decode('{
            "ok": true,
            "message": {
                "reactions": [
                    {
                        "name": "thumbsup",
                        "count": 3,
                        "users": ["U1", "U2", "U3"]
                    }
                ]
            }
        }');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
            ->method('sendRequest')
            ->willReturnCallback(function ($request) use ($expectedResponse) {
                $this->assertSame('GET', $request->getMethod());
                $this->assertSame('reactions.get', $request->getUri()->getPath());
                $this->assertSame('application/json; charset=utf-8', $request->getHeaderLine('Content-Type'));
                $response = $this->createMock(ResponseInterface::class);
                $response->method('getBody')->willReturn(json_encode($expectedResponse));
                return $response;
            });

        $slackClient = new ReactionOperations('test-token', $httpClient);

        $result = $slackClient->getReactions($channel, $timestamp);
        $this->assertEquals($expectedResponse, $result);
    }
}
