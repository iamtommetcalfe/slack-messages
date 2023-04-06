## Prerequisites
### Slack Installation
1. Go to https://api.slack.com/apps and sign in to your Slack account.
2. Click the "Create New App" button and fill out the required information.
3. Once your app is created, click on "OAuth & Permissions" in the sidebar menu.
4. Scroll down to the "Scopes" section and add the "chat:write" scope under "Bot Token Scopes". This will allow your bot to send messages to channels.
5. Click "Install App" in the sidebar menu and then click the "Install App to Workspace" button. Follow the prompts to authorize the app and grant the necessary permissions.
6. After installing the app, you will be redirected to the "OAuth & Permissions" page. Copy the "Bot User OAuth Token" and save it securely. You will need it later when configuring your PHP package.


## Installation
```shell
composer install
```

## Examples
Slack API Reference https://api.slack.com/methods

```php
$channelId = 'C03D0SLK3QC';

$message = 'Hello, World!';

$ts = '1234567890.123456';

$messageSender = new \SlackMessages\MessageSender($slackToken);

// This sends a message to a channel
$response = $messageSender->sendMessage($channelId, $message);

// This updates a message in a channel
$updateResponse = $messageSender->updateMessage($channelId, $message, $ts);

// This deletes a message in a channel
$deleteResponse = $messageSender->deleteMessage($channelId, $ts);

$ephemeralMessage = new \SlackMessages\EphemeralMessage($slackToken);

// Instead of posting regular messages, you can use the sendEphemeralMessage method to send messages that are visible only to a specific user in a conversation.
$userId = 'U12345678';
$ephemeralResponse = $ephemeralMessage->sendEphemeralMessage($channelId, $message, $userId);

$reactionOperations = new \SlackMessages\ReactionOperations($slackToken);

// Adds a reaction to a message
$emojiName = 'thumbsup';
$reactionResponse = $reactionOperations->addReaction($channelId, $ts, $emojiName);

// Get reactions
$reactionGetResponse = $reactionOperations->getReactions($channelId, $ts);

// Removes a reaction from a message
$reactionDeleteResponse = $reactionOperations->deleteReaction($channelId, $ts, $emojiName);

$scheduledMessage = new \SlackMessages\ScheduledMessage($slackToken);

// Schedules a message
$postAt = 1672845600;
$scheduledResponse = $scheduledMessage->scheduleMessage($channelId, $message, $postAt);

// Lists all scheduled messages
$scheduledListResponse = $scheduledMessage->listScheduledMessages($channelId);

// Delete a scheduled message
$scheduledMessageId = '1234567890.123456';
$scheduledDeleteResponse = $scheduledMessage->deleteScheduledMessage($channelId, $scheduledMessageId);
```

