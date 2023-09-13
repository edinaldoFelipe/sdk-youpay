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
    $response = $charge->register([
      'description' => 'charge test',
      'amount' => 9.99,
      'name_notification' => 'James',
      'cellphone_notification' => '81997723214',
      'email_notification' => 'edinaldosantyago@hotmail.com',
    ]);
    self::$charge_id = $response->id;
    $this->assertArrayHasKey('id', (array)$response);
  }

  public function testFindChargeSuccess()
  {
    $charge = new Charge();
    $response = $charge->find(self::$charge_id);
    $this->assertArrayHasKey('id', (array)$response);
  }

  public function testGenerateBarcodeSuccess()
  {
    $charge = new Charge();
    $response = $charge->payment([
      'amount' => 9.0,
      'idCharge' => self::$charge_id,
      'customerName' => 'Edinaldo Felipe',
      'customerDocument' => '07921246427'
    ]);
    $this->assertArrayHasKey('id', (array)$response->charge);
  }

  public function testListChargeSuccess()
  {
    $charge = new Charge();
    $response = $charge->index();
    $this->assertIsArray($response);
  }

  public function testDeleteChargeSuccess()
  {
    $charge = new Charge();
    $response = $charge->destroy(self::$charge_id);
    $this->assertEquals('Sucesso', $response->msg);
  }
}
