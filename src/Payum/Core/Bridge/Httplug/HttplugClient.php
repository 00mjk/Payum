<?php

namespace Payum\Core\Bridge\Httplug;

use Http\Client\HttpClient;
use Payum\Core\HttpClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * This is a HttpClient that support Httplug. This is an adapter class that make sure we can use Httplug without breaking
 * backward compatibility. At 2.0 we will be using Http\Client\HttpClient.
 *
 * @deprecated This will be removed in 2.0. Consider using Http\Client\HttpClient.
 */
class HttplugClient implements HttpClientInterface
{
    /**
     * @var HttpClient
     */
    private $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    public function send(RequestInterface $request)
    {
        return $this->client->sendRequest($request);
    }
}
