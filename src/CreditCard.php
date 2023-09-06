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
   * Register charge
   * 
   * @param string pagadorDocumentoTipo		Defined 1 - 1 CPF, 2 CNPJ
   * @param string pagadorDocumentoNumero		CPF or CNPJ
   * @param string pagadorNome
   * @return mixed
   */
  public function payment(array $params = [])
  {
    return $this->create('payment', $params);
    // return $this->create($this->getDefaultValuesToRegisterCharge((object)$params));
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
    return [
      "numero" => preg_replace('/[^\d]/', '', $params->numero),
      "bandeira" => self::getFlag($params->numero),
      "codigoSeguranca" => $params->codigoSeguranca,
      "validadeMes" => str_pad($params->validadeMes, 2, 0, STR_PAD_LEFT),
      "validadeAno" => substr($params->validadeAno, -2),
      "nomeTitular" => $params->nomeTitular
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
    }
  }
}
