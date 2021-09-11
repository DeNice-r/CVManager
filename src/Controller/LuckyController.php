<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LuckyController extends AbstractController
{
    public function number() : Response
    {
        return $this->render('lucky/number.html.twig', [
            'number' => random_int(0, 100)
        ]);
    }

}

?>