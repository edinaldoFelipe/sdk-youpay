<?php

use PHPUnit\Framework\TestCase;
use Smart\SdkYoupay\CreditCard;
use Dotenv\Dotenv;
use Smart\SdkYoupay\Charge;

class CreditCardTest extends TestCase
{
  static $credit_card_id;

  protected function setUp(): void
  {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
  }

  public function testCreateChargeSuccess()
  {
    $charge = new CreditCard();
    $result = $charge->register([
      'description' => 'charge test',
      'installments_max_allow' => '12',
      'amount' => 9.99,
      'name_notification' => 'James',
      'cellphone_notification' => '81997723214',
      'email_notification' => 'edinaldosantyago@hotmail.com',
    ]);
    self::$credit_card_id = $result->id;
    $this->assertArrayHasKey('id', (array)$result);
  }

  public function testPaymentCreditCardSuccess()
  {
    $charge = new CreditCard();
    $result = $charge->payment([
      'amount' => 9.99,
      'idCharge' => self::$credit_card_id,
      'customerName' => 'Edinaldo Felipe',
      'numberCard' => '4000000000000010',
      'nameCard' => 'edinaldo',
      'cvcCard' => '293',
      'expiryCard' => '09/27',
      'posPaymentType' => 'credit',
      'customerDocument' => '07921246427',
      'customerEmail' => 'edinaldosantyago@hotmail.com',
      'customerCellphone' => '47989272323',
      'customerStreet' => 'rua das flores',
      'customerNumber' => '123',
      'customerDistrict' => 'Centro',
      'customerCity' => 'Caruaru',
      'customerState' => 'PE',
      'customerPostalCode' => '88000000',
      'installments' => '12',
      'idCard' => 'card_Ln9qW60iYilQjXAw',
    ]);
    var_dump($result);

    $this->assertArrayHasKey('id', (array)$result);
  }

  // estorno
}
