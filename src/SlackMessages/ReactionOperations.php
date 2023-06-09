<?php

namespace SlackMessages;

use SlackMessages\Interface\ReactionOperationsInterface;
use stdClass;

/**
 * ReactionOperations class for reacting to messages in Slack channels.
 * Implements the ReactionOperationsInterface.
 */
class ReactionOperations extends AbstractSlackClient implements ReactionOperationsInterface
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
    public function addReaction(string $channel, string $timestamp, string $emoji): stdClass
    {
        $request = $this->createRequest('POST', 'reactions.add', [
            'name' => $emoji,
            'channel' => $channel,
            'timestamp' => $timestamp,
        ]);

        return $this->executeRequest($request);
    }

    /**
     * Get reactions for a specific message.
     *
     * @param string $channel The ID of the channel containing the message.
     * @param string $timestamp The timestamp of the message to get reactions for.
     * @return stdClass The JSON decoded response from the Slack API.
     */
    public function getReactions(string $channel, string $timestamp): stdClass
    {
        $queryParams = http_build_query([
            'channel' => $channel,
            'timestamp' => $timestamp,
        ]);

        $request = $this->createRequest('GET', 'reactions.get?' . $queryParams, []);

        return $this->executeRequest($request);
    }

    /**
     * Removes an emoji reaction to a message in a Slack channel.
     *
     * @param string $channel The channel ID where the message is located.
     * @param string $timestamp The timestamp of the message to remove the reaction from.
     * @param string $emoji The emoji to be removed as a reaction (e.g., 'thumbsup').
     *
     * @return stdClass The JSON decoded response from the Slack API.
     */
    public function deleteReaction(string $channel, string $timestamp, string $emoji): stdClass
    {
        $request = $this->createRequest('POST', 'reactions.remove', [
            'name' => $emoji,
            'channel' => $channel,
            'timestamp' => $timestamp,
        ]);

        return $this->executeRequest($request);
    }
}
