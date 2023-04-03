<?php

namespace Tests\SlackMessages;

use Psr\Http\Message\RequestInterface;
use SlackMessages\EphemeralMessage;
use SlackMessages\Interface\HttpClientInterface;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;
use stdClass;

class EphemeralMessageTest extends TestCase
{
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

        // Instantiate the EphemeralMessage with the mock HttpClient
        $slackClient = new EphemeralMessage('test-token', $mockHttpClient);

        // Call the sendEphemeralMessage method and check the result
        $result = $slackClient->sendEphemeralMessage($channel, $user, $text);

        // Assert that the result is an instance of stdClass and has the 'ok' property set to true
        $this->assertInstanceOf(stdClass::class, $result);
        $this->assertTrue($result->ok);
    }
}
