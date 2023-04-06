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

    /**
     * Removes an emoji reaction to a message in a Slack channel.
     *
     * @param string $channel The channel ID where the message is located.
     * @param string $timestamp The timestamp of the message to remove the reaction from.
     * @param string $emoji The emoji to be removed as a reaction (e.g., 'thumbsup').
     *
     * @return stdClass The JSON decoded response from the Slack API.
     */
    public function deleteReaction(string $channel, string $timestamp, string $emoji): stdClass;

    /**
     * Get reactions for a specific message.
     *
     * @param string $channel The ID of the channel containing the message.
     * @param string $timestamp The timestamp of the message to get reactions for.
     * @return stdClass The API response as an object.
     */
    public function getReactions(string $channel, string $timestamp): stdClass;
}
