<?php

namespace SlackMessages\Interface;

use stdClass;

interface ScheduledMessageInterface
{
    /**
     * Schedules a message to be sent to a Slack channel at a specific time.
     *
     * @param string $channelId The channel ID to send the message to.
     * @param string $text The message text.
     * @param int $postAt The Unix timestamp for when the message should be posted.
     * @return stdClass A stdClass containing the entire response from the Slack API.
     */
    public function scheduleMessage(string $channelId, string $text, int $postAt): stdClass;

    /**
     * Retrieves a list of scheduled messages for a given Slack channel.
     *
     * @param string $channelId The channel ID to fetch scheduled messages from.
     * @return stdClass A stdClass containing the entire response from the Slack API.
     */
    public function listScheduledMessages(string $channelId): stdClass;

    /**
     * Deletes a scheduled message in a Slack channel.
     *
     * @param string $channelId The channel ID where the scheduled message is located.
     * @param string $scheduledMessageId The ID of the scheduled message to delete.
     * @return stdClass A stdClass containing the entire response from the Slack API.
     */
    public function deleteScheduledMessage(string $channelId, string $scheduledMessageId): stdClass;
}
