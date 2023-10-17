<?php

/**
 * Class to manager charges billet
 */

namespace Smart\SdkYoupay;

class Charge extends Resource
{

  /**
   * Base url
   * 
   * @return string
   */
  public function endpoint(): string
  {
    return 'api/charge/';
  }

  /**
   * Register charge
   * 
   * @param bool allow_card                       Default false
   * @param bool allow_pix                        Default true
   * @param bool allow_boleto                     Default true
   * @param bool use_only_once                    Default true
   * @param string description
   * @param string type_transaction_installments  Default FULL    Options FULL | INSTALL_WITH_INTEREST | INSTALL_NO_INTEREST
   * @param string installments_max_allow         Default 1
   * @param float amount
   * @param string due_at                         Default Today
   * @param string name_notification
   * @param string cellphone_notification
   * @param string email_notification
   * @param string type_transaction               Default LATER   Options LATER | NOW
   * 
   * @return mixed
   */
  public function register(array $params = [])
  {
    return $this->create('new', $this->processParamsNewCharge((object)$params));
  }

  /**
   * Generate method payment
   * 
   * @param string                        Default bank_slip  Options bank_slip | pix | card | pos
   * @param float amount
   * @param string idCharge
   * @param string customerName
   * @param string customerTypeDocument   Default CPF
   * @param string customerDocument
   * 
   * @return mixed
   */
  public function payment(array $params = [])
  {
    return $this->create('payment', $this->processParamsNewPayment((object)$params));
  }

  /**
   * Get charge by ID
   * 
   * @param string $id	 	ID of the charge
   * @return mixed
   */
  public function find(string $id)
  {
    return $this->retrieve($id);
  }

  /**
   * Get all charges
   * 
   * @param string $id	 	ID of the charge
   * @return mixed
   */
  public function index()
  {
    return $this->all();
  }

  /**
   * Delete charge by ID
   * 
   * @param string $id	 	ID of the charge
   * @return mixed
   */
  public function destroy(string $id)
  {
    return $this->delete("delete/$id");
  }

  /**
   * Process create required params
   * 
   * @param stdClass $params
   * @return array
   */
  private function processParamsNewCharge(\stdClass $params): array
  {
    return [
      'allow_card' => $params->allow_card ?? false,
      'allow_pix' => $params->allow_pix ?? true,
      'allow_boleto' => $params->allow_boleto ?? true,
      'use_only_once' => $params->use_only_once ?? true,
      'description' => $params->description ?? null,
      // FULL | INSTALL_WITH_INTEREST | INSTALL_NO_INTEREST
      'type_transaction_installments' => $params->type_transaction_installments ?? 'FULL',
      'installments_max_allow' => $params->installments_max_allow ?? 1,
      'amount' => preg_replace('/[^\d.]/', '', $params->amount),
      'due_at' => $params->due_at ?? date('Y-m-d'),
      'name_notification' =>  $params->name_notification ?? null,
      'cellphone_notification' => preg_replace('/[^\d]/', '', $params->cellphone_notification),
      'email_notification' => $params->email_notification ?? null,
      'type_transaction' => $params->type_transaction ?? 'NOW', // LATER | NOW
    ];
  }

  /**
   * Process payment required params
   * 
   * @param stdClass $params
   * @return array
   */
  private function processParamsNewPayment(\stdClass $params): array
  {
    return [
      ...($params->method ? (array)$params : []),
      ...[
        'method' => $params->method ?? 'bank_slip', // bank_slip | pix | card | pos
        'amount' => preg_replace('/[^\d.]/', '', $params->amount),
        'idCharge' => $params->idCharge,
        'customerName' => $params->customerName,
        'customerTypeDocument' => $params->customerTypeDocument ?? 'CPF',
        'customerDocument' => preg_replace('/[^\d]/', '', $params->customerDocument),
      ]
    ];
  }
}

// method' => 'card',
//       'amount' => preg_replace('/[^\d.]/', '', $params->amount),
//       'idCharge' => $params->idCharge,
//       'customerName' => $params->customerName,
//       'posPaymentType' => $params->posPaymentType ?? 'credit', // credit | debit | voucher
//       'customerTypeDocument' => $params->customerTypeDocument ?? 'CPF',
//       'customerDocument' => preg_replace('/[^\d]/', '', $params->customerDocument),
//       'customerEmail' => $params->customerEmail,
//       'customerCellphone' => preg_replace('/[^\d]/', '', $params->customerCellphone),
//       'customerStreet' => $params->customerStreet,
//       'customerNumber' => $params->customerNumber,
//       'customerDistrict' => $params->customerDistrict,
//       'customerCity' => $params->customerCity,
//       'customerState' => substr($params->customerState, 0, 2),
//       'customerPostalCode' => preg_replace('/[^\d]/', '', $params->customerPostalCode),
//       'installments' => $params->installments,
//       'idCard'
