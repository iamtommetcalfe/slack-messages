## Prerequisites
### Slack Installation
1. Go to https://api.slack.com/apps and sign in to your Slack account.
2. Click the "Create New App" button and fill out the required information.
3. Once your app is created, click on "OAuth & Permissions" in the sidebar menu.
4. Scroll down to the "Scopes" section and add the "chat:write" scope under "Bot Token Scopes". This will allow your bot to send messages to channels.
5. Click "Install App" in the sidebar menu and then click the "Install App to Workspace" button. Follow the prompts to authorize the app and grant the necessary permissions.
6. After installing the app, you will be redirected to the "OAuth & Permissions" page. Copy the "Bot User OAuth Token" and save it securely. You will need it later when configuring your PHP package.

### .env file
1. Copy the .env.example file and rename as .env
2. Add your above token to the SLACK_TEST_TOKEN variable in .env

## Installation
```shell
composer install
```

## Examples

```php
$slackClient = new SlackClient($slackToken);

// This sends a message to a channel
$response = $slackClient->sendMessage($channelId, $message);

// This updates a message in a channel
$updateResponse = $slackClient->updateMessage($channelId, $text, $ts);

// Instead of posting regular messages, you can use the sendEphemeralMessage method to send messages that are visible only to a specific user in a conversation.
$ephemeralResponse = $slackClient->sendEphemeralMessage($channelId, $message, $userId);

// This deletes a message in a channel
$deleteResponse = $slackClient->deleteMessage($channelId, $ts);
```

