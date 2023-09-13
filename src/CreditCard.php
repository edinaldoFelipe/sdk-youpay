<?php

/**
 * Manage purchase by credit card
 */

namespace Smart\SdkYoupay;

class CreditCard extends Resource
{
  /**
   * Endpoint complement
   * 
   * @return string
   */
  public function endpoint(): string
  {
    return 'api/charge/';
  }

  /**
   * Register charge by credit card
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
      ...[
        'allow_card' => true,
        'allow_pix' => false,
        'allow_boleto' => false,
        'type_transaction_installments' => 'INSTALL_WITH_INTEREST',
      ],
      ...$params
    ]);
  }

  /**
   * Payment by credit card
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
  public function payment(array $params = [])
  {
    $charge = new Charge();
    return $charge->payment($this->getDefaultValuesCreditCard((object)$params));
  }

  /**
   * Get charge by ID
   * 
   * @param string $id	 	ID of the charge
   * @return mixed
   */
  public function find(string $id)
  {
    $charge = new Charge();
    return $charge->retrieve($id);
  }

  /**
   * Reversal payment by credit card
   * 
   * @param string paymentId
   * @param string chargeId
   * @return mixed
   */
  public function reversal(string $paymentId, string $chargeId)
  {
    return $this->delete("cancel/$chargeId?id_payment=$paymentId");
  }

  /**
   * Fill required values to credit card
   * 
   * @param stdClass $params
   * @return mixed
   */
  private function getDefaultValuesCreditCard(\stdClass $params): array
  {
    if (!$params->idCard) {
      $cofre = new Cofre();
      $cofre = $cofre->register(
        [
          'description' => "Credit card from $params->customerName",
          'cardholder_name' => $params->customerName,
          'cpf' => $params->customerDocument,
          'card_number' => $params->numberCard,
          'expiration_info' => $params->expiryCard,
          'cvc' => $params->cvcCard,
          'email' => $params->customerEmail,
          'phone' => $params->customerCellphone,
        ]
      );
      $params->idCard = $cofre->id;
    }

    return [
      'method' => 'card',
      'amount' => preg_replace('/[^\d.]/', '', $params->amount),
      'idCharge' => $params->idCharge,
      'customerName' => $params->customerName,
      'posPaymentType' => $params->posPaymentType ?? 'credit', // credit | debit | voucher
      'customerTypeDocument' => $params->customerTypeDocument ?? 'CPF',
      'customerDocument' => preg_replace('/[^\d]/', '', $params->customerDocument),
      'customerEmail' => $params->customerEmail,
      'customerCellphone' => preg_replace('/[^\d]/', '', $params->customerCellphone),
      'customerStreet' => $params->customerStreet,
      'customerNumber' => $params->customerNumber,
      'customerDistrict' => $params->customerDistrict,
      'customerCity' => $params->customerCity,
      'customerState' => substr($params->customerState, 0, 2),
      'customerPostalCode' => preg_replace('/[^\d]/', '', $params->customerPostalCode),
      'installments' => $params->installments,
      'idCard' => $params->idCard,
    ];
  }

  /**
   * Get credit card flag by number
   * 
   * @param string $number
   * @return string
   */
  public static function getFlag(string $number): string
  {
    if (substr($number, 0, 1) == '4')
      return 'visa';
    else if (substr($number, 0, 4) == '6011')
      return 'discover';

    switch (substr($number, 0, 2)) {
      case '34':
      case '37':
        return 'americanexpress';
        break;
      case '36':
      case '38':
        return 'dinnersclub';
        break;
      case '35':
        return 'jcb';
        break;
      case '51':
      case '52':
      case '53':
      case '54':
      case '55':
        return 'mastercard';
        break;
      case '65':
        return 'discover';
        break;
      default:
        '';
        break;
    }
  }
}
