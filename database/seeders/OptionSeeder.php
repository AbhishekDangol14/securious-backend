<?php

namespace Database\Seeders;

use App\Models\Option;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        // Check value exists or not already, If exists then skip it else insert new data
        $options = Option::select('key')->whereIn('key', ['admin_assets', 'admin_social_assets', 'admin_browsers', 'admin_operating_systems'])->get();
    
        $options = $options->toArray();
      
        $optionsArr = []; 
        
        $keyColumn = [];

        if (!empty($options)) {
            $keyColumn = array_column($options, 'key');
        }

        // if (!in_array('admin_assets', $keyColumn)) {
        //     $optionsArr[] = [
        //         'key'         => 'admin_assets',
        //         'value'       => $this->getTechnologies(),
        //         'type'        => 'admin_assets',
        //         'description' => 'These assets used at admin partner solutions area',
        //         'created_at'  => Carbon::now(),
        //     ];
        // }

        if (!in_array('admin_social_assets', $keyColumn)) {
            $optionsArr[] = [
                'key'         => 'admin_social_assets',
                'value'       => json_encode($this->createSocialAssets()),
                'type'        => 'admin_assets',
                'description' => 'These assets used at admin partner solutions area',
                'created_at'  => Carbon::now(),
            ];
        }

        if (!in_array('admin_browsers', $keyColumn)) {
            $optionsArr[] = [
                'key'         => 'admin_browsers',
                'value'       => json_encode($this->createBrowserAssets()),
                'type'        => 'admin_assets',
                'description' => 'These assets used at admin partner solutions area',
                'created_at'  => Carbon::now(),
            ];
        }

        if (!in_array('admin_operating_systems', $keyColumn)) {
            $optionsArr[] = [
                'key'         => 'admin_operating_systems',
                'value'       => json_encode($this->createOsAssets()),
                'type'        => 'admin_assets',
                'description' => 'These assets used at admin partner solutions area',
                'created_at'  => Carbon::now(),
            ];
        }
        if (!empty($optionsArr)) {
            Option::insert(
                $optionsArr
            );
        }
    }

    private function getTechnologies()
    {
        $client = new Client(['verify' => false ]);

        $res = $client->request('GET', 'https://www.who-hosts-this.com/API/List');

        $statusCode = $res->getStatusCode();

        if ($statusCode === 200):
            return $res->getBody();
        endif;
    }

    private function createSocialAssets()
    {
        return [
            'Facebook',
            'YouTube',
            'WhatsApp',
            'Instagram',
            'TikTok',
            'Snapchat',
            'Reddit',
            'Pinterest',
            'Twitter',
            'LinkedIn',
        ];
    }

    private function createBrowserAssets()
    {
        return [
            'Chrome',
            'Firefox',
            'Internet Explorer',
            'Edge',
            'Opera',
            'Safari'
        ];
    }

    private function createOsAssets()
    {
        return [
            'MacOS', 
            'Windows', 
            'Android', 
            'iOS',
            'Ubuntu'
        ];
    }
}
