<?php

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;

class EnvironmentVariablesTest extends TestCase
{
  protected function setUp(): void
  {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
  }

  public function testYoupayEstablishmentIsSet()
  {
    $this->assertArrayHasKey('YOUPAY_ID_ESTABLISHMENT', $_ENV);
  }


  public function testYoupayClientIsSet()
  {
    $this->assertArrayHasKey('YOUPAY_CLIENT_ID', $_ENV);
  }


  public function testYoupaySecretIsSet()
  {
    $this->assertArrayHasKey('YOUPAY_SECRET_KEY', $_ENV);
  }

  public function testYoupayUrlIsSet()
  {
    $this->assertArrayHasKey('YOUPAY_URL', $_ENV);
  }
}
