<?php

namespace Tests\SlackMessages;

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
}
