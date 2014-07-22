<?php

namespace Book\RequestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BookRequestBundle:Default:index.html.twig', array('name' => $name));
    }
}
