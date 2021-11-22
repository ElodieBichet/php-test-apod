<?php

namespace App\Service;

use DateTime;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApodAPIService
{
    private $httpClient;
    private $apod_api_key;

    public function __construct(string $apod_api_key, HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->apod_api_key = $apod_api_key;
    }

    public function getPictureOfTheDay(string $date = null): array
    {
        // If no date is provided, $date = today
        if (!$date) $date = date("Y-m-d");

        $response = $this->httpClient->request(
            'GET',
            'https://api.nasa.gov/planetary/apod',
            [
                'query' => [
                    'api_key' => $this->apod_api_key,
                    'date'    => $date
                ]
            ]
        );

        return $response->toArray();
    }
}
