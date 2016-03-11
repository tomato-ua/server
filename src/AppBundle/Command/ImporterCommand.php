<?php

// src/AppBundle/Command/GreetCommand.php

namespace AppBundle\Command;

use AppBundle\Entity\Link;
use AppBundle\Entity\Stenography;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class ImporterCommand extends Command
{
    /**
     * @var Registry
     */
    private $doctrine;

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
        $em = $this->getDoctrine()->getManager();

        $domain = 'http://iportal.rada.gov.ua';
        $link = $domain . '/meeting/search/page/1?search_convocation=8&search_session=0&search_string=&search_type=1&submit=%D0%97%D0%BD%D0%B0%D0%B9%D1%82%D0%B8';
        $crawler = new Crawler(file_get_contents($link));

        $crawler->filter('.meeting_search_result')->each(function (Crawler $node) use ($domain, $em) {
            $date = \DateTime::createFromFormat('d.m.Y', $node->filter('span.date')->text())->setTime(0, 0, 0);
            $steno = $em->getRepository('AppBundle:Stenography')->findOneBy(['eventDate' => $date]);

            if (!$steno) {
                $steno = new Stenography();
                $steno->setEventDate($date);
                $em->persist($steno);
                $em->flush();
            }

            $title = $node->filter('h4.title')->text();
            $url = $domain . $node->filter('div > a')->attr('href');
            $uniqId = md5($title);

            $link = $em->getRepository('AppBundle:Link')->findOneBy(['stenography' => $steno, 'url' => $url]);

            if (!$link) {
                $link = new Link();
                $link->setTitle($title);
                $link->setUrl($url);
                $link->setStenography($steno);
                $link->setUniqueId($uniqId);
                $em->persist($link);
                $em->flush();
            }
        });
    }
}
