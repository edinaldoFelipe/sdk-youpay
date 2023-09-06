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
      'allow_card' => true,
      'allow_pix' => true,
      'allow_boleto' => true,
      'use_only_once' => true,
      'description' => 'charge test',
      'type_transaction_installments' => 'credit',
      'installments_max_allow' => '12',
      'amount' => 9.99,
      'due_at' => '2023-09-07',
      'id_user_created_charge' => '3',
      'name_notification' => 'James',
      'cellphone_notification' => '81997723214',
      'email_notification' => 'edinaldosantyago@hotmail.com',
      'type_transaction' => 'LATER'
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
    $this->assertNull($result);
  }
}
