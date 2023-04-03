<?php

namespace SlackMessages;

use GuzzleHttp\Client as GuzzleClient;
use SlackMessages\Interface\HttpClientInterface;

/**
 * HttpClient class extends Guzzle's Client and implements the HttpClientInterface
 * to provide a custom HTTP client compatible with the SlackMessages library.
 *
 * Since this class extends Guzzle's Client, no additional methods are needed here.
 * You can add any custom methods for your HttpClient if needed in the future.
 */
class HttpClient extends GuzzleClient implements HttpClientInterface
{
    // No additional methods are needed at the moment.
    // This class is intentionally left empty to provide
    // a place to add custom methods for the HttpClient
    // if needed in the future.
}
