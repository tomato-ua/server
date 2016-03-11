<?php

// src/AppBundle/Command/GreetCommand.php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class ImporterCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('importer:fetch')
            ->setDescription('run controlpoints generator')
            ->addArgument(
                'sample', InputArgument::OPTIONAL, 'If set, generate controls for this sample'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

//        $em = $this->getContainer()->get('doctrine')->getEntityManager();
//        $em->flush();


        $domain = 'http://iportal.rada.gov.ua';

        $link = $domain . '/meeting/search/page/1?search_convocation=8&search_session=0&search_string=&search_type=1&submit=%D0%97%D0%BD%D0%B0%D0%B9%D1%82%D0%B8';

        $crawler = new Crawler(file_get_contents($link));

            foreach ($crawler->filter('.meeting_search_result') as $node) {


                $children = $node->childNodes;
                foreach ($children as $child) {


                    if($child->nodeName == 'div'){
                        echo $child->ownerDocument->saveXML( $child );
                        echo "\n";
                    }
                    if($child->nodeName == 'span'){
                        echo $child->ownerDocument->saveXML( $child );
                        echo "\n";
                    }


///                    var_dump($child->nodeName);

//                    echo $child->tagname();
//
                }

        }


    }


}
