<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class LuckyController extends AbstractController
{
    /**
     * @Route("/lucky", name="lucky")
     */
    public function index() : Response
    {
        return $this->render('lucky/index.html.twig', [
            'controller_name' => 'LuckyController',
            'number' => random_int(0, 100)
        ]);
    }

}

?>