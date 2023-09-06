<?php

/**
 * Manager Requests and Responses
 */

namespace Smart\SdkYoupay;

use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Smart\SdkYoupay\Http\Client;

class ResourceRequester
{
  /**
   * @var \Smart\SdkYoupay\Http\Client
   */
  public $client;

  public function __construct(array $config = [])
  {
    $this->client = new Client($config);
  }

  /**
   * @param string $method	HTTP Method.
   * @param string $endpoint	Relative to API base path.
   * @param array	 $options	Options for the request.
   *
   * @return mixed
   * @throws \GuzzleHttp\Exception\GuzzleException
   */

  public function request(string $method, string $endpoint, ?array $options = [])
  {
    try {
      $response = $this->client->request($method, $endpoint, $options);
    } catch (ClientException $error) {
      $response = $error->getResponse();
    }

    return $this->response($response);
  }

  /**
   * @return object
   * @throws RateLimitException
   */
  public function response(ResponseInterface $response)
  {
    return json_decode($response->getBody()->getContents());
  }
}
