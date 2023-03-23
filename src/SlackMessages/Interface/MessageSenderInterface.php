<?php
namespace SlackMessages\Interface;

use stdClass;

interface MessageSenderInterface
{
    /**
     * Sends a regular message to a Slack channel.
     *
     * @param string $channelId The channel to send the message to.
     * @param string $text The message text.
     * @return stdClass The JSON object representing the API response.
     */
    public function sendMessage(string $channelId, string $text): stdClass;

    /**
     * Sends an ephemeral message to a user in a Slack channel.
     *
     * @param string $channelId The channel where the message will be shown.
     * @param string $user The user who will see the ephemeral message.
     * @param string $text The message text.
     * @return stdClass The JSON object representing the API response.
     */
    public function sendEphemeralMessage(string $channelId, string $user, string $text): stdClass;

    /**
     * Updates an existing message in a Slack channel.
     *
     * @param string $channelId The channel containing the message to update.
     * @param string $text The new message text.
     * @param string $ts The timestamp of the message to update.
     * @return stdClass The JSON object representing the API response.
     */
    public function updateMessage(string $channelId, string $text, string $ts): stdClass;
}
