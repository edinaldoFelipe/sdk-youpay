<?php

use PHPUnit\Framework\TestCase;
use Smart\SdkYoupay\Charge;
use Dotenv\Dotenv;

class ChargeTest extends TestCase
{
  static $charge_id;

  protected function setUp(): void
  {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
  }

  public function testCreateChargeSuccess()
  {
    $charge = new Charge();
    $result = $charge->register([
      'description' => 'charge test',
      'amount' => 9.99,
      'name_notification' => 'James',
      'cellphone_notification' => '81997723214',
      'email_notification' => 'edinaldosantyago@hotmail.com',
    ]);
    self::$charge_id = $result->id;
    $this->assertArrayHasKey('id', (array)$result);
  }

  public function testFindChargeSuccess()
  {
    $charge = new Charge();
    $result = $charge->find(self::$charge_id);
    $this->assertArrayHasKey('id', (array)$result);
  }

  public function testGenerateBarcodeSuccess()
  {
    $charge = new Charge();
    $result = $charge->payment([
      'amount' => 9.0,
      'idCharge' => self::$charge_id,
      'customerName' => 'Edinaldo Felipe',
      'customerDocument' => '07921246427'
    ]);
    $this->assertArrayHasKey('id', (array)$result->charge);
  }

  public function testListChargeSuccess()
  {
    $charge = new Charge();
    $result = $charge->index();
    $this->assertIsArray($result);
  }

  public function testDeleteChargeSuccess()
  {
    $charge = new Charge();
    $result = $charge->destroy(self::$charge_id);
    $this->assertEquals('Sucesso', $result->msg);
  }
}
