<?php

namespace App\Repositories;

use App\Repositories\Contracts\SendSMSServiceRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Http\Response;

/**
 * Class SendSMSServiceRepository
 * @package App\Services
 */
class SendSMSServiceRepository implements SendSMSServiceRepositoryInterface
{
    /* @var Client */
    private $client;

    /**
     * SendSMSServiceRepository constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = new Client([
            'base_uri' => 'https://marketing.instanceshape.com',
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ]);
    }

    /**
     * @param string $textMessage
     * @param string $phone
     * @param int $phonePrefix
     */
    public function sendMessage(
        string $textMessage,
        string $phone,
        int $phonePrefix = 57
    ): void
    {
        $token = $this->login();
        if ($token != '') {
            $headers = [
                'Authorization' => 'Bearer ' . $token,
            ];

            $body = [
                'form_params' => [
                    'textMessage' => $textMessage,
                    'isTest' => env('SMS_ACTIVE_TEST'),
                    'recipients' => [
                        [
                            'phone'  => sprintf('%s%s', $phonePrefix, $phone)
                        ]
                    ]
                ],
                'headers' => $headers
            ];

            try {
                $this->client->post('/api/v1/send-message', $body);
            } catch (\Exception $e) {

            }
        }
    }

    /**
     * @return string
     */
    private function login(): string
    {
        try {
            $result = $this->client->post('/api/auth/login',
                array(
                    'form_params' => array(
                        'email' => env('SMS_EMAIL'),
                        'password' => env('SMS_PASSWORD')
                    )
                )
            );

            $response = json_decode($result->getBody());

            if ($result->getStatusCode() == Response::HTTP_OK) {
                return $response->data->token;
            }
        } catch (\Exception $e) {
            return '';
        }
    }
}
