<?php

namespace App\Http\Controllers\Helpers;
use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;

class FirebaseHelper
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
    public static function connect()
    {
        $firebase = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));

        return $firebase->createDatabase();
    }
}