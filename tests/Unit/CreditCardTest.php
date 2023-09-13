<?php

use PHPUnit\Framework\TestCase;
use Smart\SdkYoupay\CreditCard;
use Dotenv\Dotenv;
use Smart\SdkYoupay\Charge;

class CreditCardTest extends TestCase
{
  static $credit_card_id;
  static $payment_id;

  protected function setUp(): void
  {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
  }

  public function testCreateChargeCreditCardSuccess()
  {
    $charge = new CreditCard();
    $response = $charge->register([
      'description' => 'charge test',
      'installments_max_allow' => '12',
      'amount' => 9.99,
      'name_notification' => 'James',
      'cellphone_notification' => '81997723214',
      'email_notification' => 'edinaldosantyago@hotmail.com',
    ]);
    self::$credit_card_id = $response->id;
    $this->assertArrayHasKey('id', (array)$response);
  }

  public function testPaymentCreditCardSuccess()
  {
    $charge = new CreditCard();
    $response = $charge->payment([
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
      'idCard' => 'card_oBk0meRfmEFgj0W3',
    ]);

    $this->assertEquals('Pagamento processado', $response->msg);
  }

  public function testFindChargeSuccess()
  {
    $charge = new CreditCard();
    $response = $charge->find(self::$credit_card_id);
    self::$payment_id = $response->payments[0]->id;
    $this->assertIsNumeric(self::$payment_id);
  }

  public function testReversalCreditCardSuccess()
  {
    $charge = new CreditCard();
    $response = $charge->reversal(self::$payment_id, self::$credit_card_id);

    $this->assertEquals('Sucesso', $response->msg);
  }
}
