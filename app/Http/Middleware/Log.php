<?php

namespace App\Http\Middleware;

use App\Services\IpLocationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Log
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $temp = $request->all();
        $start = microtime(true);
        $response = $next($request);
        $end = microtime(true);
        $duration = ($end - $start) * 1000;
        $responseJson =$response? json_encode($response):null;
        $headersJson =$request->header()? json_encode($request->header()):null;
        if(array_key_exists('photo', $temp)) {
            unset($temp['photo']);
            $paramsJson =$temp ? json_encode($temp):null;
        } else {
            $paramsJson =$request->all() ? json_encode($request->all()):null;
        }
        $uri = $request->getRequestUri();
        $ip = $request->ip();
        $ipLocationService = app(IpLocationService::class);
        $location = $ipLocationService->getLocation($ip);
        $latitude = '';
        $longitude = '';
        $city = '';
        $country_name = '';
        if($location)
        {
            $latitude = $location['latitude'];
            $longitude = $location['longitude'];
            $city = $location['city'];
            $country_name = $location['country_name'];
        }
        if($uri=="/login")
            $paramsJson='';
        $log = DB::table('logs')
            ->insert([
                'uri'=>$uri,
                'method'=>$request->getMethod(),
                'user_id'=>auth()->user() ? auth()->user()->id : null,
                'header' => $headersJson,
                'params'=>$paramsJson,
                'duration' => $duration,
                'ip_address'=>$ip,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'city' => $city,
                'country_name' => $country_name,
                'user_agent'=>$request->header('user-agent'),
                'response_code'=> $response ? $response->getStatusCode() : null,
                'response'=> $responseJson,
                'created_at'=>date('Y-m-d H:i:s'),
            ]);
        return $response;
    }
}
