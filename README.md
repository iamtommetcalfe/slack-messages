## Installation
asd

### Prerequisites
#### Slack Installation
1. Go to https://api.slack.com/apps and sign in to your Slack account.
2. Click the "Create New App" button and fill out the required information.
3. Once your app is created, click on "OAuth & Permissions" in the sidebar menu.
4. Scroll down to the "Scopes" section and add the "chat:write" scope under "Bot Token Scopes". This will allow your bot to send messages to channels.
5. Click "Install App" in the sidebar menu and then click the "Install App to Workspace" button. Follow the prompts to authorize the app and grant the necessary permissions.
6. After installing the app, you will be redirected to the "OAuth & Permissions" page. Copy the "Bot User OAuth Token" and save it securely. You will need it later when configuring your PHP package.

## Example

```php
<?php
require 'vendor/autoload.php';

use SlackMessages\SlackClient;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$slackToken = $_ENV['SLACK_TEST_TOKEN']; // Replace this with your Bot User OAuth Token
$channel = '#random'; // Replace this with your desired Slack channel
$message = 'Hello, Slack!';

$slackClient = new SlackClient($slackToken);
$response = $slackClient->sendMessage($channel, $message);

echo $response . PHP_EOL;
```
