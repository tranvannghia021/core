<?php

namespace Devtvn\Social\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Pusher\Pusher;

class CoreHelper
{
    /**
     * encode firebase token
     * @param array $payload
     * @param bool $refresh
     * @return string
     */
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


    /**
     * decode firebase token
     * @param string $jwt
     * @param bool $refresh
     * @return mixed
     */
    public static function decodeJwt(string $jwt, bool $refresh = false)
    {
        $jwt = trim(trim($jwt, 'Bearer'));
        $key = $refresh ? config('social.key_jwt.private.key') . config('social.key_jwt.publish.key') : config('social.key_jwt.private.key');
        return json_decode(json_encode(JWT::decode($jwt, new Key($key, config('social.key_jwt.alg')))), true);
    }


    /**
     * encode firebase state
     * @param array $payload
     * @return string
     */
    public static function encodeState(array $payload)
    {
        $payload['expire'] = date("Y-m-d H:i:s", time() + config('social.key_jwt.time.token'));
        return JWT::encode($payload, config('social.key_jwt.publish.key'), config('social.key_jwt.alg'));
    }


    /**
     * decode firebase state
     * @param string $jwt
     * @return mixed
     */
    public static function decodeState(string $jwt)
    {
        return json_decode(json_encode(JWT::decode($jwt,
            new Key(config('social.key_jwt.publish.key'), config('social.key_jwt.alg')))), true);
    }

    /**
     * check token expire
     * @param string $time
     * @return bool
     */
    public static function expireToken(string $time): bool
    {
        return date("Y-m-d H:i:s", time()) > $time;
    }

    /**
     * get ip user
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
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

    /**
     * trigger socket client
     * @param string $prefix
     * @param array $data
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pusher\ApiErrorException
     * @throws \Pusher\PusherException
     */
    public static function pusher(string $prefix, array $data)
    {

        $pusher = new Pusher(
            config('social.pusher.key'),
            config('social.pusher.secret'),
            config('social.pusher.app_id'),
            config('social.pusher.options')
        );
        $pusher->trigger(config('social.pusher.channel'), config('social.pusher.event') . $prefix, $data);
    }

    /**
     * build response jwt
     * @param $userInfo
     * @return array
     */
    public static function createPayloadJwt($userInfo)
    {
        return [
            'userInfo' => $userInfo,
            'jwt' => [
                'type' => 'Bearer',
                'access_token' => self::encodeJWT([
                    'id' => @$userInfo['id'],
                    'email' => @$userInfo['email'],
                ]),
                'time_expire' => config('social.key_jwt.time.token'),
                'refresh_token' => self::encodeJWT([
                    'id' => @$userInfo['id'],
                    'email' => @$userInfo['email'],
                    'internal_id' => @$userInfo['internal_id']
                ], true),
                'time_expire_refresh' => config('social.key_jwt.time.refresh'),
            ]
        ];
    }

    /**
     * check access denied
     * @param $request
     * @return bool
     */
    public static function handleErrorSocial($request)
    {
        return $request->has('errors') || $request->has('error');
    }

    /**
     * format base64 to hyperlink image
     * @param $folder
     * @param $param
     * @return false|string
     */
    public static function saveImgBase64($folder, $param)
    {
        $fileExtension = config('social.storage.image_ext');
        $tagDisk = config('social.storage.disk');
        if (count(explode(';', $param)) != 2) {
            return false;
        }
        list($extension, $content) = explode(';', $param);
        $tmpExtension = explode('/', $extension);
        if (!in_array($tmpExtension[1], $fileExtension)) {

            return false;
        }
        preg_match('/.([0-9]+) /', microtime(), $m);
        $fileName = sprintf('img%s%s.%s', date('YmdHis'), $m[1], $tmpExtension[1]);
        $content = explode(',', $content)[1];
        $storage = Storage::disk($tagDisk);

        $checkDirectory = $storage->exists($folder);

        if (!$checkDirectory) {
            $storage->makeDirectory($folder);
        }
        $storage->put($folder . '/' . $fileName, base64_decode($content), $tagDisk);
        return $fileName;
    }

    /**
     * remove data private
     * @param $user
     * @return void
     */
    public static function removeInfoPrivateUser(&$user)
    {
        unset($user['password'], $user['access_token'],
            $user['refresh_token'],
            $user['expire_token'],
        );
    }

    /**
     * push ip into variable
     * @param $payload
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function setIpState(&$payload)
    {
        $result = self::ip();
        $payload['ip'] = request()->ip();
        if ($result['status']) {
            $payload['ip'] = $result['ip'];
        }
    }


}
