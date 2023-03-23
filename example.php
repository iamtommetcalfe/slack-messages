<?php
require 'vendor/autoload.php';

use SlackMessages\SlackClient;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$slackToken = $_ENV['SLACK_TEST_TOKEN']; // Replace this with your Bot User OAuth Token
$channel = 'C03D0SLK3QC'; // Replace this with your desired Slack channel
$message = 'Hello, Slassssck!';

$slackClient = new SlackClient($slackToken);
$response = $slackClient->deleteMessage($channel, '1679580636.942559');

//echo $response . PHP_EOL;
