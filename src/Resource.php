<?php

/**
 * Main methods GET, POST, PUT, PATCH and DELETE
 */

namespace Smart\SdkYoupay;

abstract class Resource
{

  /**
   * @var \Smart\SdkYoupay\ResourceRequester
   */
  public $resource_requester;

  /**
   * Create Requester
   * 
   * @param array $config
   * @return ResourceRequester
   */
  public function __construct(array $config = [])
  {
    $this->resource_requester = new ResourceRequester($config);
  }

  /**
   * Define endpoint method
   */
  abstract public function endpoint(): string;

  /**
   * Complete Url
   * 
   * @return string
   */
  public function url(?string $id = null, ?string $route = null): string
  {
    $endpoint = $this->endpoint();

    if (!is_null($id)) {
      $endpoint .= $id;
    }

    if (!is_null($route)) {
      $endpoint .= $route;
    }

    return $endpoint;
  }

  /**
   * Create a new resource.
   *
   * @param array $params The request body.
   *
   * @return mixed
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function create(?string $route, array $params = [])
  {
    return $this->resource_requester->request(
      'POST',
      $this->url(null, $route),
      ['json' => $params]
    );
  }

  /**
   * Retrieve all resources with no params required.
   *
   * @return mixed
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function all()
  {
    return $this->resource_requester->request('GET', $this->url());
  }

  /**
   * Retrieve a specific resource.
   *
   * @param int/string $id The resource's id.
   *
   * @return mixed
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function retrieve(?string $id = null)
  {
    return $this->resource_requester->request('GET', $this->url($id));
  }

  /**
   * Update a specific resource by using patch.
   *
   * @param int 	$id				The resource's id.
   * @param array $params	The request body.
   *
   * @return mixed
   * @throws \GuzzleHttp\Exception\GuzzleException*
   */
  public function updateSome(array $params = [])
  {
    return $this->resource_requester->request(
      'PATCH',
      $this->url(),
      ['json' => $params]
    );
  }

  /**
   * Update a specific resource.
   *
   * @param array $params	The request body.
   *
   * @return mixed
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function update($id, array $params = [])
  {
    return $this->resource_requester->request(
      'PUT',
      $this->url($id),
      ['json' => $params]
    );
  }

  /**
   * Delete a specific resource.
   *
   * @param int	$id				The resource's id.
   * @param array $params 	The request body.
   *
   * @return mixed
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function delete(?string $id = null)
  {
    return $this->resource_requester->request('DELETE', $this->url($id));
  }

  /**
   * Make a GET request to an additional endpoint for a specific resource.
   *
   * @param int	$url		The resource's id with additional enpoint.
   * @param array $params		Get params.
   *
   * @return mixed
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function get(?string $url = null, array $params = [])
  {
    return $this->resource_requester->request(
      'GET',
      $this->url($url, $this->arrayToUrlParams($params))
    );
  }

  /**
   * Convert array to url params
   * 
   * @param array $params
   * @return string
   */
  private function arrayToUrlParams(array $params): ?string
  {
    if (empty($params))
      return null;

    $array = [];

    foreach ($params as $key => $value)
      $array[] = "$key=$value";

    return '?' . implode('&', $array);
  }

  /**
   * Make a POST request to an additional endpoint for a specific resource.
   *
   * @param int	 $id				 	The resource's id.
   * @param string $additionalEndpoint 	Additional endpoint that will be appended to the URL.
   * @param array	 $params			The request body.
   *
   * @return mixed
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function post(?string $id = null, ?string $route = null, array $params = [])
  {
    return $this->resource_requester->request(
      'POST',
      $this->url($id, $route),
      ['json' => $params]
    );
  }
}
