<?php

use PHPUnit\Framework\TestCase;
use Smart\SdkYoupay\Cofre;
use Dotenv\Dotenv;

class CofreTest extends TestCase
{
  static $cofre_id;

  protected function setUp(): void
  {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
  }

  public function testCreateCofreSuccess()
  {
    $cofre = new Cofre();
    $result = $cofre->register([
      'description' => 'Test Card',
      'cardholder_name' => 'EDINALDO FELIPE S',
      'cpf' => '07921246427',
      'card_number' => '4000000000000010',
      'expiration_info' => '12/27',
      'cvc' => 926,
      'email' => 'edinaldosantyago@hotmail.com',
      'phone' => '47989274343',
    ]);
    self::$cofre_id = $result->details->card_id;
    $this->assertArrayHasKey('card_id', (array)$result->details);
  }
}
