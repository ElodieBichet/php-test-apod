<?php

namespace App\Service;

use App\Entity\Media;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApodAPIService
{
    private $apod_api_key;
    private $httpClient;
    private $serializer;

    public function __construct(
        string $apod_api_key,
        HttpClientInterface $httpClient,
        SerializerInterface $serializer
    ) {
        $this->apod_api_key = $apod_api_key;
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
    }

    public function getMediaOfTheDay($format = 'json', string $date = null)
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

        return ($format == 'json') ? $response->getContent() : $response->toArray();
    }

    public function createMediaFromAPOD(string $date = null): Media
    {
        $data = $this->getMediaOfTheDay('json', $date);
        $media = $this->serializer->deserialize($data, Media::class, 'json');

        return $media;
    }
}
