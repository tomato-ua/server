<?php

// src/AppBundle/Command/GreetCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\Stenography;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Constraints\DateTime;

class TaggerCommand extends ContainerAwareCommand
{

    private $baseDir;

    protected function configure()
    {
        $this
            ->setName('tagger:tag')
            ->setDescription('run controlpoints generator')
            ->addArgument(
                'sample', InputArgument::OPTIONAL, 'If set, generate controls for this sample'
            );

        $this->baseDir = str_replace('/src/AppBundle/Command', '', __DIR__);

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        date_default_timezone_set("Europe/Kiev");


        $rexSafety = "/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+/i";


        $em = $this->getContainer()->get('doctrine')->getEntityManager();


        /**
         * @var  Video $video
         */

        $video = $em->getRepository('AppBundle:Video')->findOneBy(['tagged' => false]);

        $command = "/usr/local/php5/bin/php {$this->baseDir}/cli.php " . $video->getYoutubeId();

        `$command`;

        $data = file_get_contents($this->baseDir . '/videoresult.txt');

        $data = json_decode($data);


        $startTime = new \DateTime($data->time);

        $video->setStartTime($startTime);

        $links = $video->getStenography()->getLinks()->getValues();

        $url = current($links);

        $data = [];

        $isTag = false;
        $crawler = new Crawler(file_get_contents($url->getUrl()));

        $time = false;

        $data = [];

        foreach ($crawler->filter('.MsoNormal') as $key => $node) {

            if ($time && $isTag && strpos($node->textContent, 'Дякую') === false && !preg_match($rexSafety, $node->textContent) && strlen($node->textContent) < 40 && strlen($node->textContent) > 3) {
//                echo $node->textContent . "\n";
                $data[] = ['name' => $node->textContent, 'time' => $time];
            } elseif (preg_match("/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/", $node->textContent)) {
                //              echo $node->textContent . "\n";
                $time = $node->textContent;
                $isTag = true;
            } else {
                $time = false;
                $isTag = false;
            }

        }

        foreach ($data as $user) {
            $tag = new Tag($startTime);
            $tag->setTitle($user['name']);
            $tag->setStringTime($user['time']);
            $tag->setVideo($video);

            $em->persist($tag);
        }

      $em->flush();

    }


}
