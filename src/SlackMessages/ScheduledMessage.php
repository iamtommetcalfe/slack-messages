<?php

namespace SlackMessages;

use SlackMessages\Interface\ScheduledMessageInterface;
use stdClass;

/**
 * ScheduledMessage class for sending scheduled messages to Slack channels.
 * Implements the ScheduledMessageInterface.
 */
class ScheduledMessage extends AbstractSlackClient implements ScheduledMessageInterface
{
    /**
     * Schedules a message to be sent to a Slack channel at a specific time.
     *
     * @param string $channelId The channel ID to send the message to.
     * @param string $text The message text.
     * @param int $postAt The Unix timestamp for when the message should be posted.
     * @return stdClass A stdClass containing the entire response from the Slack API.
     */
    public function scheduleMessage(string $channelId, string $text, int $postAt): stdClass
    {
        $request = $this->createRequest('POST', 'chat.scheduleMessage', [
            'channel' => $channelId,
            'text' => $text,
            'post_at' => $postAt,
        ]);

        return $this->executeRequest($request);
    }

    /**
     * Retrieves a list of scheduled messages for a given Slack channel.
     *
     * @param string $channelId The channel ID to fetch scheduled messages from.
     * @return stdClass A stdClass containing the entire response from the Slack API.
     */
    public function listScheduledMessages(string $channelId): stdClass
    {
        $request = $this->createRequest('GET', 'chat.scheduledMessages.list', [
            'channel' => $channelId,
        ]);

        return $this->executeRequest($request);
    }

    /**
     * Deletes a scheduled message in a Slack channel.
     *
     * @param string $channelId The channel ID where the scheduled message is located.
     * @param string $scheduledMessageId The ID of the scheduled message to delete.
     * @return stdClass A stdClass containing the entire response from the Slack API.
     */
    public function deleteScheduledMessage(string $channelId, string $scheduledMessageId): stdClass
    {
        $request = $this->createRequest('POST', 'chat.deleteScheduledMessage', [
            'channel' => $channelId,
            'scheduled_message_id' => $scheduledMessageId,
        ]);

        return $this->executeRequest($request);
    }
}
