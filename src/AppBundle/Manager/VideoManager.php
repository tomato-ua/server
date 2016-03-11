<?php

namespace AppBundle\Manager;

use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VideoManager
{
    /**
     * @var string
     */
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getVideos()
    {
        $client = new Client(['base_uri' => 'https://www.googleapis.com']);

        try {
            $request = $client->get('/youtube/v3/search', [
                'query' => [
                    'key' => $this->apiKey,
                    'channelId' => 'UCEf6u1pcxUaz3pUWrl5_E5w',
                    'part' => 'snippet,id',
                    'order' => 'date',
                    'maxResults' => 50,
                    'q' => '19.02.2016'
                ]
            ]);

            return $request->getBody()->getContents();
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}