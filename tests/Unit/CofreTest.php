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

  // public function testCreateCofreSuccess()
  // {
  //   $cofre = new Cofre();
  //   $result = $cofre->register([]);

  //   self::$cofre_id = $result->id;
  //   $this->assertArrayHasKey('id', (array)$result);
  // }

  // public function testFindCofreSuccess()
  // {
  //   $cofre = new Cofre();
  //   $result = $cofre->find(self::$cofre_id);
  //   $this->assertArrayHasKey('id', (array)$result);
  // }

  // public function testDeleteCofreSuccess()
  // {
  //   $cofre = new Cofre();
  //   $result = $cofre->destroy(self::$cofre_id);
  //   $this->assertNull($result);
  // }
}
