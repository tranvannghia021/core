<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;
use Pusher\Pusher;

class CoreHelper
{
    public static function encodeJwt(array $payload, bool $refresh = false)
    {
        if ($refresh) {
            $payload['expire'] = date("Y-m-d H:i:s", time() + config('social.key_jwt.time.refresh'));
            return JWT::encode($payload, config('social.key_jwt.private.key') . config('social.key_jwt.publish.key'),
                config('social.key_jwt.alg'));
        } else {
            $payload['expire'] = date("Y-m-d H:i:s", time() + config('social.key_jwt.time.token'));
            return JWT::encode($payload, config('social.key_jwt.private.key'), config('social.key_jwt.alg'));

        }
    }


    public static function decodeJwt(string $jwt, bool $refresh = false)
    {
        $jwt = trim(trim($jwt, 'Bearer'));
        $key = $refresh ? config('social.key_jwt.private.key') . config('social.key_jwt.publish.key') : config('social.key_jwt.private.key');
        return json_decode(json_encode(JWT::decode($jwt, new Key($key, config('social.key_jwt.alg')))), true);
    }


    public static function encodeState(array $payload)
    {
        $payload['expire'] = date("Y-m-d H:i:s", time() + config('social.key_jwt.time.token'));
        return JWT::encode($payload, config('social.key_jwt.publish.key'), config('social.key_jwt.alg'));
    }


    public static function decodeState(string $jwt)
    {
        return json_decode(json_encode(JWT::decode($jwt, new Key(config('social.key_jwt.publish.key'), config('social.key_jwt.alg')))), true);
    }

    public static function expireToken(string $time) :bool{
        return date("Y-m-d H:i:s",time()) > $time;
    }

    public static function ip()
    {
        $ip = request()->ip() ?? null;
        $client = new Client();
        $url = "http://ip-api.com/json/$ip";
        try {
            $response = $client->request('GET', $url,
                [
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ],
                    'query' => [],
                ]
            );
            $result = json_decode($response->getBody()->getContents(), true);
            // Check ip is valid.
            $ip = $result['query'] ?? null;
            if(!filter_var($ip, FILTER_VALIDATE_IP)){
                $ip = null;
            }

            return [
                'status' => true,
                'data' => $result,
                'ip' => $ip
            ];
        } catch (\Exception $exception) {
            return [
                'status' => false,
                'data' => $exception,
                'ip' => null,
            ];
        }

    }

    public static function pusher(string $prefix, array $data){
        $pusher = new Pusher(
            config('social.pusher.key'),
            config('social.pusher.secret'),
            config('social.pusher.app_id'),
            config('social.pusher.options')
        );
        $pusher->trigger(config('social.pusher.channel'), config('social.pusher.event').$prefix, $data);
    }

    public static function createPayloadJwt($userInfo){
        return[
            'userInfo'=>$userInfo,
            'jwt'=>[
                    'type'=>'Bearer',
                    'access_token'=>self::encodeJWT([
                        'id'=>@$userInfo['id'],
                        'email'=>@$userInfo['email'],
                    ]),
                    'time_expire'=>config('social.key_jwt.time.token'),
                    'refresh_token'=>self::encodeJWT([
                        'id'=>@$userInfo['id'],
                        'email'=>@$userInfo['email'],
                        'internal_id'=>@$userInfo['internal_id']
                    ],true),
                    'time_expire_refresh'=>config('social.key_jwt.time.refresh'),
                ]
        ];
    }

    public static function handleErrorSocial($request){
       return $request->has('errors') || $request->has('error');
    }

}
