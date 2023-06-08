<?php

namespace Devtvn\Social\Ecommerces\RestApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Request
{
    /**
     * @var mixed
     */
    protected $_client;

    /**
     * @var mixed
     */
    protected $token;

    /**
     * @var mixed
     */
    protected $scope;

    /**
     * @var mixed
     */
    protected $endpoint;

    /**
     * @var mixed
     */
    protected $version;

    /**
     * @var mixed
     */
    protected $clientId;

    /**
     * @var mixed
     */
    protected $secretId;

    /**
     * @var mixed
     */
    protected $redirect;

    /**
     * @var mixed
     */
    protected $refresh;

    /**
     * construct
     */
    public function __construct()
    {
        $this->_client = new Client();
    }


    /**
     * @param string $url
     * @param array $header
     * @return array
     */
    public function getRequest(string $url, array $header = []): array
    {
        try {
            $response = $this->_client->request('GET', $url,
                [
                    'headers' => $header
                ]
            );
            return [
                'status' => true,
                'data' => json_decode($response->getBody()->getContents(), true)
            ];
        } catch (GuzzleException $exception) {
            return $this->handleRequestError($exception);
        }
    }

    /**
     * @param string $url
     * @param array $header
     * @param array $data
     * @return array
     */
    public function postRequestFormParams(string $url, array $header = [], array $data = []): array
    {
        try {
            $response = $this->_client->request('POST', "$url",
                [
                    'headers' => $header,
                    'form_params' => $data
                ]);
            return ['status' => true, 'data' => json_decode($response->getBody()->getContents(), true)];
        } catch (GuzzleException $exception) {
            return $this->handleRequestError($exception);
        }
    }

    /**
     * @param string $url
     * @param array $header
     * @param array $data
     * @return array
     */
    public function postRequest(string $url, array $header = [], array $data = [], array $auth = []): array
    {
        try {
            $response = $this->_client->request('POST', "$url",
                [
                    'headers' => $header,
                    'body' => json_encode($data),
                    'auth' => $auth
                ]);
            return [
                'status' => true,
                'code' => $response->getStatusCode(),
                'data' => json_decode($response->getBody()->getContents(), true)
            ];
        } catch (GuzzleException $exception) {
            return $this->handleRequestError($exception);
        }
    }

    /**
     * @param string $url
     * @param array $headers
     * @param array $data
     * @return array
     */
    public function putRequest(string $url, array $headers = [], array $data = []): array
    {
        try {
            $response = $this->_client->request(
                'PUT', $url,
                [
                    'headers' => $headers,
                    'body' => json_encode($data)
                ]);
            return [
                'status' => true,
                'code' => $response->getStatusCode(),
                'data' => json_decode($response->getBody()->getContents(), true)
            ];

        } catch (GuzzleException $exception) {
            return $this->handleRequestError($exception);
        }
    }


    /**
     * @param string $url
     * @param array $headers
     * @param array $data
     * @return array
     */
    public function deleteRequest(string $url, array $headers = [], array $data = []): array
    {
        try {
            $response = $this->_client->request('DELETE', $url,
                [
                    'headers' => $headers,
                    'body' => json_encode($data)
                ]);

            return [
                'status' => true,
                'code' => $response->getStatusCode(),
                'data' => json_decode($response->getBody()->getContents(), true)
            ];
        } catch (GuzzleException $exception) {
            return $this->handleRequestError($exception);
        }
    }

    /**
     * @param $exception
     * @return array
     */
    protected function handleRequestError($exception): array
    {
        $strResponse = $exception->getResponse()->getBody()->getContents();
        try {
            $response = json_decode($strResponse, true);
            return [
                'status' => false,
                'code' => $exception->getCode(),
                'message' => $response['errors'][0]['message'],
                'errors' => $response['errors']
            ];
        } catch (\Exception $e) {
            return ['status' => false, 'code' => $exception->getCode(), 'message' => $exception->getMessage()];
        }
    }
}
