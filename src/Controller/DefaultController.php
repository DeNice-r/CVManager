<?php

namespace App\Controller;

use App\Entity\CVReaction;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
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
            'users' => count($users),
        ]);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $request->request;
        if ($post->get('isSubmitted')) {
            $user = $entityManager->getRepository(User::class)->find($this->getUser()->getId());
            $user->setAdditionalInfo([
                'website' => $post->get('website'),
                'address' => $post->get('address'),
                'phone' => $post->get('phone'),
            ]);
            $entityManager->flush();
        }
        // Форма для сохранения информации про компанию
        $user = $this->getUser();

        return $this->render('default/profile.html.twig', [
            'controller_name' => 'DefaultController',
            'currentUser' => $user,
        ]);
    }

    /**
     * @Route("/stats", name="stats")
     */
    public function stats(): Response
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $cvreactionRepos = $entityManager->getRepository(CVReaction::class);
        $cvreactions = $cvreactionRepos->findBy([
            'userId' => $user->getId()
        ]);

        $cvstats = [];
        foreach($cvreactions as $cvr){
            $cvid = $cvr->getcvId();
            $cvv = $cvr->getCVVersion()->format("j.n.Y H:i:s");
            $reaction = $cvr->getReaction();
            $reactiontype = is_null($reaction) ? -1 : $reaction;

            if(isset($cvstats[$cvid][$cvv][$reactiontype]))
                $cvstats[$cvid][$cvv][$reactiontype] += 1;
            else
                $cvstats[$cvid][$cvv][$reactiontype] = 1;
        }
        
        return $this->render('default/stats.html.twig', [
            'controller_name' => 'DefaultController',
            'stats' => $cvstats,
            'myCVs' => array_reverse($cvreactions)
        ]);
    }
}
