<?php

// src/AppBundle/Command/GreetCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\Stenography;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Validator\Constraints\DateTime;

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

        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $data = [];

        $domain = 'http://iportal.rada.gov.ua';
        $link = $domain . '/meeting/search/page/1?search_convocation=8&search_session=0&search_string=&search_type=1&submit=%D0%97%D0%BD%D0%B0%D0%B9%D1%82%D0%B8';
        $crawler = new Crawler(file_get_contents($link));
        foreach ($crawler->filter('.meeting_search_result') as $key => $node) {
            foreach ($node->childNodes as $child) {
                if ($child->nodeName == 'span') {
                    $date = new \DateTime();
                    $data[$key]['date'] = $date->createFromFormat('d.m.Y', $child->textContent);
                }
                if ($child->nodeName == 'h4') {
                    $data[$key]['title'] = $child->textContent;
                }
                if ($child->nodeName == 'div') {
                    foreach ($child->childNodes as $link) {
                        $data[$key]['title'] = $link->textContent . ' ' . $data[$key]['title'];
                        $data[$key]['url'] = $domain . $link->getAttribute('href');
                    }
                }
            }
        }

        foreach ($data as $item) {
            $s = new Stenography();
            $s->setTitle($item['title']);
            $s->setEventDate($item['date']);
            $s->setUrl($item['url']);
            $s->setUniqueId(md5($item['title']));
            $em->persist($s);
        }

        $em->flush();

    }


}
