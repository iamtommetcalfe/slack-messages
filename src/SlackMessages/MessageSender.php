<?php

namespace SlackMessages;

use SlackMessages\Interface\MessageSenderInterface;
use stdClass;

/**
 * MessageSender class for sending messages, updating and deleting messages in Slack channels.
 * Implements the MessageSenderInterface.
 */
class MessageSender extends AbstractSlackClient implements MessageSenderInterface
{
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

        return $this->executeRequest($request);
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

        return $this->executeRequest($request);
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

        return $this->executeRequest($request);
    }
}
