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

    public function getVideos(\DateTime $date)
    {
        $client = new Client(['base_uri' => 'https://www.googleapis.com']);

        dump($date->format('d.m.Y'));
        try {
            $request = $client->get('/youtube/v3/search', [
                'query' => [
                    'key' => $this->apiKey,
                    'channelId' => 'UCEf6u1pcxUaz3pUWrl5_E5w',
                    'part' => 'id,snippet',
                    'order' => 'date',
                    'maxResults' => 50,
                    'q' => $date->format('d.m.Y')
                ]
            ]);

            return json_decode($request->getBody()->getContents(), true);
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}