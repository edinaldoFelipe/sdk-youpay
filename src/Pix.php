<?php

/**
 * Manage Pix
 */

namespace Smart\SdkYoupay;

class Pix extends Resource
{
  /**
   * Url complement
   * 
   * @return string
   */
  public function endpoint(): string
  {
    return 'api/charge/qrcode/static/';
  }

  /**
   * Create new Pix Purchase Avulse
   * 
   * @return mixed
   */
  public function registerAvulse(array $params = [])
  {
    return $this->create(null, $params);
  }

  /**
   * Register pix
   * 
   * @param string description
   * @param float amount
   * @param string due_at                         Default Today
   * @param string name_notification
   * @param string cellphone_notification
   * @param string email_notification
   * 
   * @return mixed
   */
  public function register(array $params = [])
  {
    $charge = new Charge();
    return $charge->register([
      ...['allow_boleto' => false],
      ...$params
    ]);
  }

  /**
   * Payment pix
   * 
   * @param string description
   * @param float amount
   * @param string due_at                         Default Today
   * @param string name_notification
   * @param string cellphone_notification
   * @param string email_notification
   * 
   * @return mixed
   */
  public function generateQRCode(array $params = [])
  {
    $charge = new Charge();
    return $charge->payment([
      ...['method' => 'pix'],
      ...$params
    ]);
  }

  /**
   * Get pix Purchase by ID
   * 
   * @param string $id
   * @return mixed
   */
  public function find(string $id)
  {
    return $this->retrieve($id);
  }
}
