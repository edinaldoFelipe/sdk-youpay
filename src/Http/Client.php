<?php

/**
 * Generate Client Request
 */

namespace Smart\SdkYoupay\Http;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\GuzzleException;
use Smart\SdkYoupay\Config;

class Client extends Guzzle
{

  /**
   * create authorized client
   * 
   * @return void
   */
  public function __construct(array $config = [])
  {
    try {
      self::refreshToken();
      parent::__construct([
        ...[
          'base_uri' => Config::getURL(),
          'headers' => [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => 'Bearer ' . Config::getAccessToken(),
            'idEstablishment' => Config::getEstabelishmentId(),
          ]
        ],
        ...$config
      ]);
    } catch (GuzzleException $error) {
      throw $error;
    }
  }

  static public function refreshToken(): void
  {
    if (!Config::getAccessToken() || !Config::getExpireTime() || (Config::getExpireTime() - time() < 0)) {
      $credentials = Client::generateAuthenticationCurl();
      $envContents = file_get_contents('.env');
      $envContents = preg_replace('/YOUPAY_ACCESS_TOKEN=(.*)/', 'YOUPAY_ACCESS_TOKEN=' . $credentials->access_token, $envContents);
      $envContents = preg_replace('/YOUPAY_EXPIRE_TIME=(.*)/', 'YOUPAY_EXPIRE_TIME=' . time() + $credentials->expires_in, $envContents);

      file_put_contents('.env', $envContents);
    }
  }

  /**
   * Generate valide access token
   * 
   * @throws \Exception
   */
  static public function generateAuthenticationCurl(): \stdClass
  {
    try {
      $response = (new Guzzle())->request('POST', Config::getUrlOauth(), [
        'body' => json_encode([
          ...(array)Config::getCredentials(),
          ...['scope' => 'charges', 'grant_type' => 'client_credentials']
        ]),
        'headers' => [
          'accept' => 'application/json',
          'content-type' => 'application/json',
        ],
      ]);
      return json_decode($response->getBody()->getContents());
    } catch (GuzzleException $error) {
      throw $error;
    }
  }
}
