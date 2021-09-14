<?php

namespace App\Controller;

use App\Entity\User;
use phpDocumentor\Reflection\Types\Resource_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index() : Response
    {
        $userRepos = $this->getDoctrine()->getRepository(User::class);
        // $qb = $this->getDoctrine()->getManagerForClass(User::class)->;
        // $userQB = $qb;
        $companies = $userRepos
        ->findBy(['representsCompany' => 1], null, 10);
        $users = $this->getDoctrine()
        // Must be rewritten to use query and COUNT(user.id) WHERE `representsCompany` = 0
        ->getRepository(User::class)
        ->findBy(['representsCompany' => 0]);

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'companies' => $companies,
            'users' => count($users)
        ]);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(): Response
    {
        // Форма для сохранения информации про компанию
        return $this->render('default/profile.html.twig', [
            'controller_name' => 'DefaultController'
            
        ]);
    }

    /**
     * @Route("/stats", name="stats")
     */
    public function stats(): Response
    {
        return $this->render('default/stats.html.twig', [
            'controller_name' => 'DefaultController'
            
        ]);
    }

}

?>