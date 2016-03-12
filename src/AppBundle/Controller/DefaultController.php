<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $video = $this->getDoctrine()->getRepository('AppBundle:Video')
            ->createQueryBuilder('v')
            ->join('v.stenography', 's')
            ->orderBy('s.eventDate',  'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $this->render('default/index.html.twig', [
            'video' => $video
        ]);
    }
}
