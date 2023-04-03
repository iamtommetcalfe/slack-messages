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
$slackClient = new SlackClient($slackToken);

$channelId = 'C03D0SLK3QC';

$message = 'Hello, World!';

$ts = '1234567890.123456';

// This sends a message to a channel
$response = $slackClient->sendMessage($channelId, $message);

// This updates a message in a channel
$updateResponse = $slackClient->updateMessage($channelId, $message, $ts);

// Instead of posting regular messages, you can use the sendEphemeralMessage method to send messages that are visible only to a specific user in a conversation.
$user = 'U12345678';
$ephemeralResponse = $slackClient->sendEphemeralMessage($channelId, $message, $userId);

// This deletes a message in a channel
$deleteResponse = $slackClient->deleteMessage($channelId, $ts);

// Adds a reaction to a message
$emojiName = 'thumbsup';
$reactionResponse = $slackClient->addReaction($channelId, $ts, $emojiName);

// Schedules a message
$postAt = 1672845600;
$scheduledResponse = $slackClient->scheduleMessage($channelId, $message, $postAt);

// Lists all scheduled messages
$scheduledListResponse = $slackClient->listScheduledMessages($channelId);

// Delete a scheduled message
$scheduledMessageId = '1234567890.123456';
$scheduledDeleteResponse = $slackClient->deleteScheduledMessage($channelId, $scheduledMessageId);
```

