<?php

namespace SlackMessages;

use SlackMessages\Interface\EphemeralMessageInterface;
use stdClass;

/**
 * EphemeralMessage class for sending ephemeral messages to Slack channels.
 * Implements the EphemeralMessageInterface.
 */
class EphemeralMessage extends AbstractSlackClient implements EphemeralMessageInterface
{
    /**
     * Sends an ephemeral message to a Slack channel, visible only to a specific user.
     *
     * @param string $channelId The channel ID to send the message to.
     * @param string $user The ID of the user who will see the ephemeral message.
     * @param string $text The message text.
     * @return stdClass A stdClass containing the entire response from the Slack API
     */
    public function sendEphemeralMessage(string $channelId, string $user, string $text): stdClass
    {
        $request = $this->createRequest('POST', 'chat.postEphemeral', [
            'channel' => $channelId,
            'text' => $text,
            'user' => $user,
        ]);

        return $this->executeRequest($request);
    }
}
