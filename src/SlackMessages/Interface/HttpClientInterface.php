<?php
namespace SlackMessages\Interface;

use Psr\Http\Client\ClientInterface;

/**
 * HttpClientInterface extends the PSR-18 ClientInterface to provide
 * a contract for creating custom HTTP clients compatible with the
 * SlackMessages library.
 *
 * Currently, no additional methods are needed here.
 * You can add any custom methods for your HttpClientInterface if needed in the future.
 */
interface HttpClientInterface extends ClientInterface
{
    // No additional methods are needed at the moment.
    // This interface is intentionally left empty to provide
    // a place to add custom methods for the HttpClientInterface
    // if needed in the future.
}
