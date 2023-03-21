<?php
namespace SlackMessages\Interface;

/**
 * MessageSenderInterface defines a contract for sending messages to various messaging platforms.
 */
interface MessageSenderInterface
{
    /**
     * Sends a message to a specified channel or recipient.
     *
     * @param string $channel The channel or recipient to send the message to.
     * @param string $text The content of the message to be sent.
     * @return string A string describing the success or failure of the message sending.
     */
    public function sendMessage(string $channel, string $text): string;
}
