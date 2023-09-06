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
   * @param string pagadorDocumentoTipo		Defined 1 - 1 CPF, 2 CNPJ
   * @param string pagadorDocumentoNumero		CPF or CNPJ
   * @param string pagadorNome
   * @return mixed
   */
  public function register(array $params = [])
  {
    return $this->create('new', $params);
    // return $this->create($this->getDefaultValuesToRegisterCharge((object)$params));
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
   * Process required params
   * 
   * @param stdClass $params
   * @return array
   */
  private function getDefaultValuesToRegisterCharge(\stdClass $params): array
  {
    $numberDocument = $this->formatDocument($params->pagadorDocumentoNumero);

    return [
      'pagadorDocumentoTipo' => strlen($numberDocument) > 11 ? 2 : 1,
      'pagadorDocumentoNumero' => $numberDocument,
      'pagadorNome' => substr($params->pagadorNome ?? '', 0, 40),
      'pagadorEndereco' => substr($params->pagadorEndereco ?? '', 0, 40),
      'pagadorBairro' => $params->pagadorBairro ?? null,
      'pagadorCidade' => substr($params->pagadorCidade ?? '', 0, 20),
      'pagadorUf' => $params->pagadorUf ?? null,
      'pagadorCep' => preg_replace('/[^\d]/', '', $params->pagadorCep ?? ''),
      'dataVencimento' => $params->dataVencimento ?? null,
      'valorNominal' => $params->valorNominal ?? null,
      'multaPercentual' => $params->multaPercentual ?? 2,
      'multaQuantidadeDias' => $params->multaQuantidadeDias ?? 0,
      'jurosPercentual' => 2,
      'tipoDesconto' => $params->tipoDesconto ?? 0,
      'descontoValor' => $params->descontoValor ?? 0,
      'descontoDataLimite' => $params->descontoDataLimite ?? date('Y-m-d'),
      'tipoProtesto' => 0,
      'protestoQuantidadeDias' => 0,
      'baixaQuantidadeDias' => $params->baixaQuantidadeDias ?? 28,
      'mensagem' => $params->mensagem ?? '',
      'tipoTitulo' => 4,
      'seuNumero' => $params->seuNumero ?? '',
      // 'convenioId' => Config::getSACADOR_ID(),
      'pagadorEmail' => $params->pagadorEmail ?? ''
    ];
  }

  /**
   * Format Document CPF / CNPJ
   * 
   * @param string $number	CPF/CNPJ
   * @return string
   */
  private function formatDocument(string $number = ''): string
  {
    $number = preg_replace('/[^\d]/', '', $number);
    return str_pad($number, (strlen($number) > 11 ? 14 : 11), 0, STR_PAD_LEFT);
  }
}
