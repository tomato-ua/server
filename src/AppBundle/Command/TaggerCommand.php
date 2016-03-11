<?php

// src/AppBundle/Command/GreetCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\Stenography;
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

    protected function configure()
    {
        $this
            ->setName('tagger:tag')
            ->setDescription('run controlpoints generator')
            ->addArgument(
                'sample', InputArgument::OPTIONAL, 'If set, generate controls for this sample'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


//        $data = file_get_contents('http://www.chesno.org/persons/json/deputies/8/');
//
//
//        $data = json_decode($data);
//
//
//        foreach($data as $item){
//            echo $item->second_name;
//        }
//
//
//        echo $data;
//
//        exit;
//

        $data = file_get_contents('http://www.youtube.com/get_video_info?video_id=5AVJd2l_Brs');



        $data = urldecode($data);


        echo "\n\n".$data."\n\n";

        exit;

       $data =  parse_str($data);

        print_r($data);


        exit;





        $rexSafety = "/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+/i";


        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        /**
         * @var  Video $video
         */

        $video = $em->getRepository('AppBundle:Video')->findOneBy(['tagged' => false]);


        $url = $video->getStenography()->getUrl();

        $data = [];

        $isTag = false;
        $crawler = new Crawler(file_get_contents($url));

        $time = false;

        $data = [];

        foreach ($crawler->filter('.MsoNormal') as $key => $node) {

            if ($time && $isTag && strpos($node->textContent, 'Дякую') === false && !preg_match($rexSafety, $node->textContent) && strlen($node->textContent) < 40 && strlen($node->textContent) > 3) {
                echo $node->textContent . "\n";
                $data[] = ['name'=>$node->textContent, 'time'=>$time];
            } elseif (preg_match("/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/", $node->textContent)) {
                echo $node->textContent . "\n";
                $time = $node->textContent;
                $isTag = true;
            }else{
                $time = false;
                $isTag = false;
            }

        }

        //       $em->flush();

    }


}
