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
     * Updates an existing message in a Slack channel.
     *
     * @param string $channelId The channel containing the message to update.
     * @param string $text The new message text.
     * @param string $ts The timestamp of the message to update.
     * @return stdClass The JSON object representing the API response.
     */
    public function updateMessage(string $channelId, string $text, string $ts): stdClass;

    /**
     * Deletes a message in a Slack channel.
     *
     * @param string $channel The channel where the message is located.
     * @param string $timestamp The timestamp of the message to delete.
     * @return stdClass The JSON object representing the API response.
     */
    public function deleteMessage(string $channel, string $timestamp): stdClass;
}
