<?php

namespace App\Services;

use GuzzleHttp\Client;

class IpLocationService
{
    protected $apiKey;
    protected $apiUrl = 'https://api.ipgeolocation.io/ipgeo';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getLocation($ip)
    {
        if ($this->isPrivateIp($ip) || $this->isLoopbackIp($ip)) {
            return false;
        }

        $client = new Client();

        try {
            $response = $client->get($this->apiUrl . '?apiKey=' . $this->apiKey . '&ip=' . $ip);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    protected function isPrivateIp($ip)
    {
        $privateRanges = [
            ['10.0.0.0', '10.255.255.255'],
            ['172.16.0.0', '172.31.255.255'],
            ['192.168.0.0', '192.168.255.255'],
        ];

        foreach ($privateRanges as list($start, $end)) {
            $startIp = ip2long($start);
            $endIp = ip2long($end);
            $ipToCheck = ip2long($ip);

            if ($ipToCheck >= $startIp && $ipToCheck <= $endIp) {
                return true;
            }
        }

        return false;
    }

    protected function isLoopbackIp($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
