<?php

namespace App\Http\Controllers\Helpers;

use Ixudra\Curl\Facades\Curl;


class SportMonksHelper
{
    protected $apiUrl = 'https://api.sportmonks.com/v3';
    protected $authToken = '';
    protected $mode = '';

    public function __construct()
    {
        $this->mode = config('sportmonks.mode');
        $this->authToken = config('sportmonks.api_key');
    }

    /**
     * Sportmonks fetchdata.
     *
     * @param string $url
     * @param string $method
     * @param string $options
     * @param array $postData
     * @return  \Illuminate\Http\JsonResponse
     */
    public function fetchPostData($url, $options = '', $postData = [], $method = 'GET')
    {
        $url = $this->apiUrl.'/'.$url.$options;
        if($method === 'POST')
        {
            return Curl::to($url)
                ->withData($postData)
                ->withAuthorization($this->authToken)
                ->asJson()
                ->post();
        } else if($method === 'GET')
        {
            return Curl::to($url)
                ->withAuthorization($this->authToken)
                ->asJson()
                ->get();
        } else
        {
            return response()->json([
                'status' => false
            ]);
        }
    }
}