<?php

namespace App\Repositories;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class WhatCms
{
    protected $apiUrl = 'https://whatcms.org/API/Tech';

    /**
     * Returns technology of given website URL
     * @param string URL
     * @return array | null
     */
    public function checkWhatCms($url)
    {
        $data = [
            'query' => [
                'key' => $this->getApiKey(),
                'url' => $url
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
        return Config::get('app.whatcms_key');
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

            return json_decode($stream);

        } catch (Exception $exception) {
            Log::channel('detect-assets')->info('Error: ' . $exception->getMessage() . ' \n Error Status Code: ' . $exception->getCode());
            return null;
        }
    }
}
