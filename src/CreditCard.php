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
   * Cancel payment same day
   * 
   * @param string pagamentoId
   * @return mixed
   */
  public function cancelCharge(string $paymentId, string $chargeId)
  {
    return $this->delete("cancel/id_charge/$chargeId?id_payment=$paymentId");
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
   * Fill required values to purchase
   * 
   * @param stdClass $params
   * @return array
   */
  private function getParamsPurchase(\stdClass $params): array
  {
    $arrayNameComprador = explode(" ", $params->comprador->nomeCompleto);
    $data = [
      'id' => $params->id,
      'valor' => number_format($params->valor),
      'comprador' => [
        'id' => $params->comprador->id,
        'documentoNumero' => preg_replace('/[^\d]/', '', $params->comprador->documentoNumero),
        'documentoTipo' => strlen(preg_replace('/[^\d]/', '', $params->comprador->documentoNumero)) > 11 ? 2 : 1,
        'email' => $params->comprador->email ?? null,
        'nomeCompleto' => $params->comprador->nomeCompleto,
        'primeiroNome' => $arrayNameComprador[0],
        'ultimoNome' => array_pop($arrayNameComprador),
        'enderecoLogradouro' => $params->comprador->enderecoLogradouro,
        'enderecoNumero' => $params->comprador->enderecoNumero ?? 'SN',
        'enderecoComplemento' => $params->comprador->enderecoComplemento ?? '',
        'enderecoCep' => preg_replace('/[^\d]/', '', $params->comprador->enderecoCep),
        'enderecoBairro' => $params->comprador->enderecoBairro,
        'enderecoCidade' => $params->comprador->enderecoCidade,
        'enderecoEstado' => $params->comprador->enderecoEstado,
        'telefone' => $params->comprador->telefone ?? ''
      ],
      'tipoTransacao' => ($params->quantidadeParcelas ?? 1) > 1 ? 'PARCELADO_SEM_JUROS' : 'A_VISTA',
      'quantidadeParcelas' => $params->quantidadeParcelas ?? 1,
      // 'convenioId' => Config::getSACADOR_ID(),
      // 'terminalId' => Config::getSACADOR_ID(),
      'gerarLinkPagamento' => $params->gerarLinkPagamento ?? false
    ];

    if ($params->cofreId)
      $data['cartao'] = [
        'id' => $params->cofreId
      ];
    else
      $data['cartao'] = [
        'numero' => preg_replace('/[^\d]/', '', $params->cartao->numero),
        'codigoSeguranca' => $params->cartao->codigoSeguranca,
        'nome' => $params->cartao->nome,
        'expiracaoAno' => substr($params->cartao->expiracaoAno, -2),
        'expiracaoMes' => str_pad($params->cartao->expiracaoMes, 2, 0, STR_PAD_LEFT),
        'bandeira' => self::getFlag($params->cartao->numero),
        'incluirCofre' => $params->cartao->incluirCofre ?? false
      ];

    return $data;
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
