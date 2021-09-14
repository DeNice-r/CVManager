<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CVManagingController extends AbstractController
{
    /**
     * @Route("manage", name="app_manage_cv")
     */
    public function index(): Response
    {
        return $this->render('cv_managing/index.html.twig', [
            'controller_name' => 'CVManagingController',
        ]);
    }
}
