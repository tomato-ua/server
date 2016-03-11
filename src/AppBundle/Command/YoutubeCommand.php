<?php

namespace AppBundle\Command;

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

    public function configure()
    {
        $this
            ->setName('youtube:load')
            ->setDescription('get videos for stenography');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getDoctrine()->getManager();

//        $stonogs = $em->getRepository('AppBundle:Stenography')->findBy([''];
    }
}
