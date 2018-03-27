<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GetCurrentTimeController extends Controller
{
    /**
     * @Route("/time", name="get_current_time")
     */
    public function index()
    {
        $t=time();
        echo("<h3>Current time: </h3>");
        echo(date("Y-m-d",$t));
        return $this->render('get_current_time/index.html.twig', [
            'controller_name' => 'GetCurrentTimeController',
        ]);
    }
}
