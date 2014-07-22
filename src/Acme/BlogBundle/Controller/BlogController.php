<?php

namespace Acme\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    public function listAction()
    {
        $posts = $this->get('doctrine')
            ->getManager()
            ->createQuery('SELECT p FROM AcmeBlogBundle:Post p')
            ->execute();
        return $this->render(
            'AcmeBlogBundle:Blog:list.html.php',
            array('posts' => $posts)
        ); }

    public function showAction($id)
    {
        $post = $this->get('doctrine')
            ->getManager()
            ->getRepository('AcmeBlogBundle:Post')
            ->find($id);
        if (!$post) {
            // cause the 404 page not found to be displayed
            throw $this->createNotFoundException();
        }
        return $this->render(
            'AcmeBlogBundle:Blog:show.html.php',
            array('post' => $post)
        ); }
}
