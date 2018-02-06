<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $obj = new Category();
        $obj-> setName('sdasd');
        $em->persist($obj);
        $em->flush();exit;
        return $this->render('AdminBundle:Default:index.html.twig');
    }
}
