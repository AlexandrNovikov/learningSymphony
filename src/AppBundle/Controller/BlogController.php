<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    /**
     * @Route("/blog")
     */
    public function indexAction()
    {
        $blog_entries = [['title' => 'first', 'body' => '1111'],['title' => 'second', 'body' => '2222']];
        return $this->render('blogs/index.html.twig', array(
            'blog_entries'=>$blog_entries
        ));
    }
}