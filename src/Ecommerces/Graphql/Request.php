<?php
namespace Devtvn\Social\Ecommerces\Graphql;
use GuzzleHttp\Client;

class Request extends \Devtvn\Social\Ecommerces\RestApi\Request
{
    /**
     * @var mixed
     */
    private $accessToken;

    /**
     * @var mixed
     */
    private $endPoint;

    /**
     * ready endpoint and token global class
     * @param $shopDomain
     * @param $accessToken
     * @return $this
     */
    public function setParameters($shopDomain, $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->endPoint    = "https://$shopDomain/admin/api/".config("social.platform.shopify.version")."/graphql.json";
        return $this;
    }

    /**
     * run query from raw query to graphql
     * @param string $query
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
   public function graphqlQuery(string $query): array
    {
        try {
            $data = $this->_client->request('POST', $this->endPoint, [
                'headers' => [
                    'Accept'                 => 'application/json',
                    'Content-Type'           => 'application/json',
                    'X-Shopify-Access-Token' => $this->accessToken
                ],
                'body'    => $query
            ]);

            $result = json_decode($data->getBody()->getContents(), true);

            if (isset($result['errors'])) {
                return ['status' => false, 'message' => \GuzzleHttp\json_encode($result['errors'])];
            }

            $this->awaitForCreditsRateLimit(@$result['extensions']);

            return ['status' => true, 'data' => $result['data']];
        } catch (\Exception $exception) {

            return ['status' => false, 'message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }

    /**
     * Handle await when rate limit
     * @param $extensions
     */
    public function awaitForCreditsRateLimit($extensions): void
    {
        if (!empty($extensions)) {
            $currentlyAvailable = $extensions['cost']['throttleStatus']['currentlyAvailable'];
            $requestedQueryCost = $extensions['cost']['requestedQueryCost'];
            while ($requestedQueryCost > $currentlyAvailable) {
                sleep(1);
                $currentlyAvailable += $extensions['cost']['throttleStatus']['restoreRate'];
            }
        }
    }
}