<?php

namespace Devtvn\Social\Ecommerces\Graphql;

class Shopify extends Request
{
    /**
     * get shop info from shopify
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function shopInfo(){
        try {
            $query = "{
                    shop {
                         id
                        name
                        primaryDomain{
                            url
                            host
                        }
                       description
                    }
                    }
                 }";

            $query = \GuzzleHttp\json_encode([
                'query' => $query
            ]);
            $data=[];
            $result=$this->graphqlQuery($query);
            if($result['status']){
                $data=@$result['data']['shop'] ?? [];
            }
            return [
                'status'=>true,
                'data'=>$data
            ];
        }catch (\Exception $exception){
            return [
                'status'=>false,
                'message'=>$exception->getMessage()
            ];
        }
    }
}