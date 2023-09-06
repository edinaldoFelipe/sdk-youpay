<?php

/**
 * Manage credit card numbers from payees
 */

namespace Smart\SdkYoupay;

class Payee extends Resource
{
  /**
   * API Version
   * 
   * @var string version
   */
  private $version = '1';

  /**
   * Endpoint complement
   * 
   * @return string
   */
  public function endpoint(): string
  {
    return "v$this->version/sacadores/";
  }

  /**
   * Get all saved credit card
   * 
   * @return mixed
   */
  public function getAllCofre()
  {
    return $this->get('cofre');
  }

  /**
   * Generate token
   * I don't know
   * 
   * @param string integracaoId
   * @return mixed
   */
  public function createToken(array $params)
  {
    return $this->get('cofre/token', $params);
  }

  /**
   * Generate token by id credit card
   * I don't know
   * 
   * @param string id		creditcard/links
   * @return mixed
   */
  public function createTokenById(string $id)
  {
    return $this->get("cofre/$id/token");
  }

  /**
   * Add save credit card
   * 
   * @param int numero
   * @param string bandeira
   * @param string nomeImpresso
   * @param string validade			mm/yy
   * @param int codigo
   * @param string integracaoId
   * @return mixed
   */
  public function addCofre(array $parameters)
  {
    $parameters['numero'] = preg_replace('/[^\d]/', '', $parameters['numero']);
    $parameters['bandeira'] = CreditCard::getFlag($parameters['numero']);

    return $this->post(null, 'cofre', $parameters);
  }

  /**
   * Remove credit card
   * 
   * @param string $id
   * @return mixed
   */
  public function removeCofre(string $id)
  {
    return $this->delete("cofre/$id");
  }

  /**
   * Create Payment Link
   * 
   * @param string dataExpiracao					yyyy-mm-dd
   * @param string compraId
   * @param string compradorId
   * @param string descricao
   * @param double valor 
   * @param string compradorNomeCompleto
   * @param string compradorDocumentoTipo			1 CPF, 2 CNPJ
   * @param string compradorDocumentoNumero		CPF/CNPJ
   * @param string compradorEmail
   * @param string compradorTelefone
   * @param string compradorEnderecoLogradouro
   * @param string compradorEnderecoNumero
   * @param string compradorEnderecoComplemento
   * @param string compradorEnderecoCep
   * @param string compradorEnderecoCidade
   * @param string compradorEnderecoEstado
   * @param bool salvarCartao 
   * @param int quantidadeMaximaParcelas
   * @return mixed
   * 
   * Use https://p4x.srv.br/pagamentos?token={token}
   */
  public function generateLink(array $parameters)
  {
    $this->version = '1.1';
    $parameters['compradorDocumentoNumero'] = preg_replace('/[^\d]/', '', $parameters['compradorDocumentoNumero'] ?? '');
    $parameters['compradorDocumentoTipo'] = strlen($parameters['compradorDocumentoNumero']) > 11 ? 2 : 1;

    return $this->post(null, 'pagamentos/token', $parameters);
  }
}
