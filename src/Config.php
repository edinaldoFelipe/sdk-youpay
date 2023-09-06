<?php

/**
 * Get default configuration variables
 */

namespace Smart\SdkYoupay;

class Config
{

  /**
   * Get object credentials to create access token
   * 
   * @return stdClass
   */
  public static function getCredentials(): \stdClass
  {
    return (object)[
      'client_establishment' => static::getEstabelishmentId(),
      'client_id' => static::getClientId(),
      'client_secret' => static::getSecret()
    ];
  }

  /**
   * Get default endpoint to create access token
   * 
   * @return string
   */
  public static function getUrlOauth(): string
  {
    return static::getURL() . 'oauth/token';
  }

  /**
   * Get base url api
   * 
   * @return string
   */
  public static function getURL(): ?string
  {
    return $_ENV['YOUPAY_URL'];
  }

  /**
   * Get Estabelishment id
   * 
   * @return string
   */
  public static function getEstabelishmentId(): ?string
  {
    return $_ENV['YOUPAY_ID_ESTABLISHMENT'];
  }

  /**
   * Get client id
   * 
   * @return string
   */
  public static function getClientId(): ?string
  {
    return $_ENV['YOUPAY_CLIENT_ID'];
  }

  /**
   * Get secret
   * 
   * @return string
   */
  public static function getSecret(): ?string
  {
    return $_ENV['YOUPAY_SECRET_KEY'];
  }

  /**
   * Get access token
   * 
   * @return string
   */
  public static function getAccessToken(): ?string
  {
    return $_ENV['YOUPAY_ACCESS_TOKEN'];
  }

  /**
   * Get expire time
   * 
   * @return string
   */
  public static function getExpireTime(): ?string
  {
    return $_ENV['YOUPAY_EXPIRE_TIME'];
  }
}
