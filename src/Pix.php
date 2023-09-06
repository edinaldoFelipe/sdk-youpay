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
   * Create new Pix Purchase
   * 
   * @param string pagadorCpfCnpj				CPF or CNPJ
   * @param string pagadorNome		
   * @param string mensagem
   * @param double valorCobrado				amount
   * @param string dataVencimento			 	yyyy-mm-dd
   * @param string webhookUrl				 	Defined null
   * @param object listaInformacoesAdicionais	Defined null
   * @return mixed
   */
  public function register(array $params = [])
  {
    // return $this->create($this->getDefaultValuesToRegisterCharge((object)$params));
    return $this->create(null, $params);
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

  /**
   * Fill required params
   * 
   * @param stdClass $params
   * @return array
   */
  private function getDefaultValuesToRegisterCharge(\stdClass $params): array
  {
    return [];
  }
}
