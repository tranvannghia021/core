<?php
namespace Devtvn\Social\Ecommerces\RestApi;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Request
{
    protected $_client,$token,$scope;
    protected $endpoint,$version,$clientId,$secretId,$redirect,$refresh;

    public function __construct()
    {
        $this->_client = new Client();
    }

    protected function implodeScope($separator = ','){
        return implode($separator,$this->scope);
    }


    public function setRefresh(string $refresh): Request
    {
        $this->refresh=$refresh;
        return $this;
    }

    public function setToken(string $token): Request
    {
        $this->token=$token;
        return $this;
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
    public function postRequest(string $url, array $header = [], array $data = []): array
    {
        try {
            $response = $this->_client->request('POST', "$url",
                [
                    'headers' => $header,
                    'body' => json_encode($data)
                ]);
            return ['status' => true, 'code' => $response->getStatusCode(), 'data' => json_decode($response->getBody()->getContents(), true)];
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
            return ['status' => true, 'code' => $response->getStatusCode(), 'data' => json_decode($response->getBody()->getContents(), true)];

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

            return ['status' => true, 'code' => $response->getStatusCode(), 'data' => json_decode($response->getBody()->getContents(), true)];
        } catch (GuzzleException $exception) {
            return $this->handleRequestError($exception);
        }
    }

    protected function handleRequestError( $exception): array
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
