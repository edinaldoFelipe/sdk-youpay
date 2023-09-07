<?php

/**
 * Class to manager cofre
 */

namespace Smart\SdkYoupay;

class Cofre extends Resource
{

  /**
   * Base url
   * 
   * @return string
   */
  public function endpoint(): string
  {
    return 'api/charge/cards/';
  }

  /**
   * Register cofre
   * 
   * @return mixed
   */
  public function register(array $params = [])
  {
    return $this->create('add', $this->processParamsNewCofre((object)$params));
  }

  /**
   * Process create required params
   * 
   * @param stdClass $params
   * @return array
   */
  private function processParamsNewCofre(\stdClass $params): array
  {
    $card_number = preg_replace('/[^\d]/', '', $params->card_number);
    return [
      'description' => $params->description,
      'cardholder_name' => $params->cardholder_name,
      'cpf' => preg_replace('/[^\d]/', '', $params->cpf),
      'card_number' => $card_number,
      'brand' => CreditCard::getFlag($card_number),
      'expiration_info' => preg_replace('/^(..).*?(..)$/', '$1/$2', $params->expiration_info),
      'cvc' => preg_replace('/[^\d]/', '', $params->cvc),
      'is_default' => false,
      'email' => $params->email,
      'phone' => preg_replace('/[^\d]/', '', $params->phone),
    ];
  }
}
