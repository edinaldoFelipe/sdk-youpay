<?php

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
use Smart\SdkYoupay\Config;
use Smart\SdkYoupay\Http\Client;

class OauthTest extends TestCase
{
  protected function setUp(): void
  {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
  }

  public function testRefreshTokenSuccess()
  {
    Client::refreshToken();
    $this->assertNotNull(Config::getAccessToken());
  }

  public function testGenerateTokenSuccess()
  {
    $credentials = Client::generateAuthenticationCurl();
    $this->assertArrayHasKey('access_token', (array)$credentials);
  }
}
