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
    return 'api/cofre/cards/';
  }

  /**
   * Register cofre
   * 
   * @return mixed
   */
  public function register(array $params = [])
  {
    return $this->create('add', $params);
    // return $this->create($this->getDefaultValuesToRegisterCofre((object)$params));
  }

  /**
   * Get cofre by ID
   * 
   * @param string $id	 	ID of the cofre
   * @return mixed
   */
  public function find(string $id)
  {
    return $this->retrieve($id);
  }

  /**
   * Delete cofre by ID
   * 
   * @param string $id	 	ID of the cofre
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
  private function getDefaultValuesToRegisterCofre(\stdClass $params): array
  {
    return [];
  }
}
