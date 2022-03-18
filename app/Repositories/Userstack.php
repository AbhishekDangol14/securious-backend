<?php

namespace App\Repositories;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class Userstack
{
    protected $apiUrl = 'http://api.userstack.com/detect';

    /**
     * Returns technology of logged in users
     * @return array | null
     */
    public function whichOsBrowser($ua)
    {
        $data = [
            'query' => [
                'access_key' => $this->getApiKey(),
                'ua' => $ua,
                'fields' => 'os,browser'
            ]
        ];

        return $this->get($this->apiUrl, $data);
    }

    /**
     * API KEY
     * @return string
     */
    protected function getApiKey(): string
    {
        return Config::get('app.userstack_key');
    }

    /**
     * Response from API
     * @param string $url
     * @param array $data
     * @return array | object | null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function get($url, $data = [])
    {
        try {
            $client = new Client(['verify' => false]);

            $response = $client->request('GET', $url, $data);

            $stream = $response->getBody();

            return json_decode($stream, true);

        } catch (Exception $exception) {
            Log::channel('detect-assets')->info('Error: ' . $exception->getMessage() . ' \n Error Status Code: ' . $exception->getCode());
            return null;
        }
    }
}
