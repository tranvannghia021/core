<?php

namespace Devtvn\Social\Service;

use Devtvn\Social\Helpers\CoreHelper;
use Devtvn\Social\Repositories\CoreRepository;
use Devtvn\Social\Traits\Response;

abstract class ACoreService implements ICoreService
{
    use Response;

    protected $platform, $userRepository, $usesPKCE = false, $code;

    public function __construct()
    {
        $this->userRepository = app(CoreRepository::class);
    }

    public function setVariable(array $variable): ACoreService
    {
        return $this;
    }

    public function generateUrl(array $payload)
    {
        return $this->Response([
            'url' => $this->platform->generateUrl($payload),
            'pusher' => [
                'channel' => config('social.pusher.channel'),
                'event' => config('social.pusher.event') . $payload['ip']
            ]
        ]);
    }

    public function auth(array $payload)
    {
        try {
            $this->beforeInstall($payload);
            $this->code = $payload['code'];
            $token = $this->getToken($payload);
            if (!$token['status']) {
                CoreHelper::pusher($payload['ip'], [
                    'status' => false,
                    'message' => 'Access denied!'
                ]);
                return;
            }
            if ($payload['type'] === 'auth') {
                $this->middleInstallBothTokenAndProfile($payload,$token);
                $user = $this->platform->setToken($token['data']['access_token'])->profile();
                $addition = $this->handleAdditional($payload, $token, $user);
                if (!$user['status']) {
                    CoreHelper::pusher($payload['ip'], [
                        'status' => false,
                        'error' => [
                            'type' => 'account_access_denied',
                            'message' => 'Access denied!',
                        ]
                    ]);
                    return;
                }


                $data = $this->getStructure($token, $user, $addition);
                $this->afterInstall($data,$token,$payload,$addition);
                $result = $this->userRepository->updateOrInsert([
                    'internal_id' => $data['internal_id'],
                    'email' => @$data['email'],
                    'platform' => $data['platform'],
                ], $data);
                $data['id'] = $result['id'];
                unset($data['password'], $data['access_token']);

                CoreHelper::pusher($payload['ip'], CoreHelper::createPayloadJwt($data));
            }
        } catch (\Exception $exception) {
            return $this->ResponseError($exception->getMessage());
        }
    }

    public abstract function getStructure(...$payload);


    public function usesPKCE()
    {
        return $this->usesPKCE;
    }

    public function getToken(array $payload)
    {
        if ($this->usesPKCE()) {
            $this->code .= ',' . $payload['code_verifier'];
        }
        return $this->platform->getAccessToken($this->code);
    }

}