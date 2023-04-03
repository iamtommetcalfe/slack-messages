<?php

namespace SlackMessages\Interface;

use stdClass;

interface ReactionOperationsInterface
{
    /**
     * Add an emoji reaction to a message in a Slack channel.
     *
     * @param string $channel The channel ID where the message is located.
     * @param string $timestamp The timestamp of the message to add the reaction to.
     * @param string $emoji The emoji to be added as a reaction (e.g., 'thumbsup').
     *
     * @return stdClass The JSON decoded response from the Slack API.
     */
    public function addReaction(string $channel, string $timestamp, string $emoji): stdClass;
}
