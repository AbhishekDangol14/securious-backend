<?php

namespace App\Repositories;
use Config;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class HBIP
{
    protected $apiUrl = 'https://haveibeenpwned.com/api/v3';

    /**
     * Returns if email security is breached  with which websites
     * @param string $email
     * @param bool $unverified
     * @return array | null
     */

    public function breachedAccount($email, $unverified = false)
    {
        $url = $this->apiUrl . "/breachedaccount/" . $email;
        $data = [
            'includeUnverified' => $unverified,
        ];
        return $this->get($url, $data);
    }

    /**
     * Response from API
     * @param string $url
     * @param array $data
     * @return array | object | null
     */
    protected function get($url, $data = [])
    {
        try {
            $client = new Client([
                'headers' => [
                    'Connection' => 'close',
                    'hibp-api-key' => $this->getApiKey()
                ],
                'http_errors' => false,
                'verify' => false
            ]);
            $response = $client->request('GET', $url, [
                'data' => $data,
            ]);
            $stream = $response->getBody();
            return json_decode($stream);
        } catch (Exception $exception) {
            Log::channel('email-breach')->info('Error: ' . $exception->getMessage() . ' \n Error Status Code: ' . $exception->getCode());
            return null;
        }
    }

    /**
     * API KEY
     * @return string
     */
    protected function getApiKey(): string
    {
        return Config::get('app.HBIP') ?? '';
    }

    /**
     * Returns All Breached Website
     * @return array
     */
    public function allBreachedWebsite(): array
    {
        $url = $this->apiUrl . "/breaches";
        return $this->get($url);
    }

    /**
     * Returns Categories of security Breach
     * @return array|null
     */

    public function dataClasses(): ?array
    {
        $url = $this->apiUrl . "/dataclasses";
        return $this->get($url);
    }
}
