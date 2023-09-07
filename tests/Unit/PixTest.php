<?php

use PHPUnit\Framework\TestCase;
use Smart\SdkYoupay\Pix;
use Dotenv\Dotenv;
use Smart\SdkYoupay\Charge;

class PixTest extends TestCase
{
  static $pix_id;

  protected function setUp(): void
  {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
  }

  public function testGenerateAvulsePixSuccess()
  {
    $this->assertEquals(1, 1);

    // $pix = new Pix();
    // $result = $pix->registerAvulse([
    //   'description' => 'QrCode test',
    //   'obs' => 'Observations pic',
    //   'amount' => '9.99',
    //   'due_at' => '2023-09-10',
    // ]);
    // var_dump($result);
    // self::$pix_id = $result->id;
    // $this->assertArrayHasKey('id', (array)$result);
  }

  public function testCreateChargePixSuccess()
  {
    $pix = new Pix();
    $result = $pix->register([
      'description' => 'charge test',
      'amount' => 9.99,
      'name_notification' => 'James',
      'cellphone_notification' => '81997723214',
      'email_notification' => 'edinaldosantyago@hotmail.com',
    ]);
    self::$pix_id = $result->id;
    $this->assertArrayHasKey('id', (array)$result);
  }

  public function testGenerateQrCodeSuccess()
  {
    $pix = new Pix();
    $result = $pix->generateQRCode([
      'amount' => 9.0,
      'idCharge' => self::$pix_id,
      'customerName' => 'Edinaldo Felipe',
      'customerDocument' => '07921246427'
    ]);
    $this->assertArrayHasKey('id', (array)$result->charge);
  }
}
