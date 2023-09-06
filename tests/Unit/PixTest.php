<?php

use PHPUnit\Framework\TestCase;
use Smart\SdkYoupay\Pix;
use Dotenv\Dotenv;

class PixTest extends TestCase
{
  static $pix_id;

  protected function setUp(): void
  {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
  }

  // public function testGenerateQRCodeSuccess()
  // {
  //   $pix = new Pix();
  //   $result = $pix->register([
  //     'description' => 'QrCode test',
  //     'obs' => 'Observations pic',
  //     'amount' => '9.99',
  //     'due_at' => '2023-09-10',
  //   ]);
  //   var_dump($result);
  //   self::$pix_id = $result->id;
  //   $this->assertArrayHasKey('id', (array)$result);
  // }
}
