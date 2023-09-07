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
   * Constructor
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

  /**
   * Check access token
   * 
   * @return void
   * @throws GuzzleException
   */
  static public function refreshToken(): void
  {
    try {
      if (!Config::getAccessToken() || !Config::getExpireTime() || (Config::getExpireTime() - time() < 0)) {
        $credentials = Client::generateAuthenticationCurl();
        $envContents = file_get_contents('.env');
        $envContents = preg_replace('/YOUPAY_ACCESS_TOKEN=(.*)/', 'YOUPAY_ACCESS_TOKEN=' . $credentials->access_token, $envContents);
        $envContents = preg_replace('/YOUPAY_EXPIRE_TIME=(.*)/', 'YOUPAY_EXPIRE_TIME=' . time() + $credentials->expires_in, $envContents);

        file_put_contents('.env', $envContents);
      }
    } catch (GuzzleException $error) {
      throw $error;
    }
  }

  /**
   * Generate valide access token
   * 
   * @return \stdClass
   * @throws GuzzleException
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
