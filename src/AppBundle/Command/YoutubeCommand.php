<?php

namespace AppBundle\Command;

use AppBundle\Entity\Video;
use AppBundle\Manager\VideoManager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class YoutubeCommand extends Command
{
    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @var VideoManager
     */
    private $youtube;

    /**
     * @return Registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @param Registry $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return VideoManager
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * @param VideoManager $youtube
     */
    public function setYoutube($youtube)
    {
        $this->youtube = $youtube;
    }

    public function configure()
    {
        $this
            ->setName('youtube:load')
            ->setDescription('get videos for stenography');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getDoctrine()->getManager();

        $stenographies = $em->getRepository('AppBundle:Stenography')->findBy(['published' => false]);

        $countVideos = 0;
        foreach ($stenographies as $stenography) {
            $videoModels = $this->youtube->getVideos($stenography->getEventDate());
            foreach ($videoModels['items'] as $model) {
                if (!$em->getRepository('AppBundle:Video')->findOneBy(['youtubeId' => $model['id']['videoId']])) {
                    $video = new Video();
                    $video->setYoutubeId($model['id']['videoId']);
                    $video->setTitle($model['snippet']['title']);
                    $video->setStenography($stenography);
                    $em->persist($video);
                    $countVideos++;
                }
            }
            $stenography->setPublished(!!$stenography->getVideos()->count());
        }
        $em->flush();

        $output->writeln("Imported $countVideos.");
    }
}
