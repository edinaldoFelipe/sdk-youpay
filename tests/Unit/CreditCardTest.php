<?php

use PHPUnit\Framework\TestCase;
use Smart\SdkYoupay\CreditCard;
use Dotenv\Dotenv;

class CreditCardTest extends TestCase
{
  static $credit_card_id;

  protected function setUp(): void
  {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
  }

  // public function testPaymentCreditCardSuccess()
  // {
  //   $credit_card = new CreditCard();
  //   $result = $credit_card->payment([]);

  //   self::$credit_card_id = $result->id;
  //   $this->assertArrayHasKey('id', (array)$result);
  // }

  // public function testFindCreditCardSuccess()
  // {
  //   $credit_card = new CreditCard();
  //   $result = $credit_card->find(self::$credit_card_id);
  //   $this->assertArrayHasKey('id', (array)$result);
  // }

  // public function testDeleteCreditCardSuccess()
  // {
  //   $credit_card = new CreditCard();
  //   $result = $credit_card->cancelCharge('idpayment', 'charge_id');
  //   $this->assertNull($result);
  // }
}
