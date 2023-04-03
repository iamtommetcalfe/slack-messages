<?php

namespace SlackMessages\Interface;

use stdClass;

interface EphemeralMessageInterface
{
    /**
     * Sends an ephemeral message to a Slack channel, visible only to a specific user.
     *
     * @param string $channelId The channel ID to send the message to.
     * @param string $user The ID of the user who will see the ephemeral message.
     * @param string $text The message text.
     * @return stdClass A stdClass containing the entire response from the Slack API
     */
    public function sendEphemeralMessage(string $channelId, string $user, string $text): stdClass;
}
