<?php

namespace App\Controller;

use App\Entity\CV;
use App\Entity\CVReaction;
use App\Entity\User;
use DateTime;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CVManagingController extends AbstractController
{
    /**
     * @Route("manage", name="manage_cv")
     */
    public function index(): Response
    {
        // Отображаем резюме, на которые ожидается резюме и резюме, которые создал пользователь
        $user = $this->getUser();
        $managedCVs = $user->getActiveCVs();
        $entityManager = $this->getDoctrine()->getManager();
        $cvRepos = $entityManager->getRepository(CV::class);
        $cvreactionRepos = $entityManager->getRepository(CVReaction::class);
        $userCVs = $cvRepos->findBy([
            'user_email' => $user->getEmail(),
        ]);
        
        $CVs = array();
        if ($managedCVs) {
            foreach ($managedCVs as $cv) {
                if(isset($cv))
                    $CVs[] = $cvreactionRepos->find($cv);
            }
        }

        return $this->render('cv_managing/index.html.twig', [
            'controller_name' => 'CVManagingController',
            'CurrentUser' => $user,
            'managedCVs' => $CVs,
            'myCVs' => $userCVs
        ]);
    }

    private function forwardToIndex()
    {
        return $this->forward('App\Controller\CVManagingController::index');
    }

    /**
     * @Route("edit", name="edit")
     */
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $get = $request->query;
        $cvid = $get->get('cvid');
        $entityManager = $this->getDoctrine()->getManager();
        $cv = null;
        if (isset($cvid)) {
            $cv = $entityManager->getRepository(CV::class)->findBy([
                'user_email' => $user->getEmail(),
                'id' => $cvid,
            ]);
            if ($cv) {
                $cv = $cv[0];
            }
        }

        $post = $request->request;
        // Если форма отправлена редактируем/создаём новое резюме
        if ($post->get('isSubmitted')) {
            $now = new DateTime();
            if (!$cv) {
                $cv = new CV();
                $cv->setCreationDate($now);
                $cv->setUserEmail($user->getEmail());
            }
            $cv->setEditedOn($now);
            $cv->setName($post->get('name'));
            $cv->setPosition($post->get('position'));
            $cv->setText($post->get('text'));

            $entityManager->persist($cv);
            $entityManager->flush();

            return $this->forwardToIndex();
        }

        return $this->render('cv_managing/edit.html.twig', [
            'controller_name' => 'CVManagingController',
            'cvid' => $cvid,
            'cv' => $cv,
            'currentUser' => $user,
        ]);
    }

    /**
     * @Route("delete", name="delete")
     */
    public function delete(Request $request): Response
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $get = $request->query;
        $cv = $entityManager->getRepository(CV::class)->findBy([
            'user_email' => $user->getEmail(),
            'id' => $get->get('cvid'),
        ]);
        if ($cv) {
            $entityManager->remove($cv[0]);
            $entityManager->flush();
        }

        return $this->forwardToIndex();
    }

    /**
     * @Route("send", name="send")
     */
    public function send(Request $request): Response
    {
        // Получить список всех компаний и вернуть на индекс если не передан айди резюме
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $userRepos = $entityManager->getRepository(User::class);
        $companies = $userRepos->findBy(['representsCompany' => 1]);
        $get = $request->query;
        $cvid = $get->get('cvid');
        if (!$cvid) {
            return $this->forwardToIndex();
        }

        // Если не передан айди компании - просто загрузить страницу
        $companyid = $get->get('companyid');
        if (!$companyid) {
            return $this->render('cv_managing/send.html.twig', [
            'controller_name' => 'CVManagingController',
            'currentUser' => $this->getUser(),
            'companies' => $companies,
            'cvid' => $cvid,
            ]);
        }

        // Получаем резюме и возвращаем на индекс если такой компании или такого резюме у пользователя нету
        $cvRepos = $entityManager->getRepository(CV::class);
        $cvreactionRepos = $entityManager->getRepository(CVReaction::class);
        $cv = $cvRepos->findBy([
            'user_email' => $user->getEmail(),
            'id' => $cvid
        ]);
        $company = $userRepos->find($companyid);
        if(!$cv or !$company)
            return $this->forwardToIndex();
        $cv = $cv[0];

        // Если эта версия резюме уже отправлена, то ничего не делать
        $cvr = $cvreactionRepos->findBy([
            'cvId' => $cvid,
            'companyId' => $companyid,
            'CVVersion' => $cv->getEditedOn()
        ]);
        if($cvr)
            return $this->forwardToIndex();

        // Создаём новый экземпляр реакции на резюме, вносим в таблицу и добавляем компании и пользователю как активные
        $now = new DateTime();
        $cvreaction = new CVReaction();
        $cvreaction->unpackCV($cv);
        $cvreaction->unpackCompany($company);
        $cvreaction->setUserId($user->getId());
        $cvreaction->setSentOn($now);
        
        // Получаем экземпляры компании и пользователя, за которыми "следит" Doctrine.
        $watchedCompany = $userRepos->find($company->getId());
        $watchedUser = $userRepos->find($user->getId());
        $entityManager->persist($cvreaction);
        $entityManager->flush();
        $watchedCompany->addActiveCV($cvreaction->getId());
        $watchedUser->addActiveCV($cvreaction->getId());
        $entityManager->flush();
        return $this->forwardToIndex();
    }

    /**
     * @Route("react", name="react")
     */
    public function react(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $get = $request->query;
        $cvreactionid = $get->get('id');
        $reaction = $get->get('reaction');
        $cvreactionRepos = $entityManager->getRepository(CVReaction::class);

        if(is_null($cvreactionid) or is_null($reaction))
            return $this->forwardToIndex();

        $cvr = $cvreactionRepos->findBy([
            'companyId' => $user->getId(),
            'id' => $cvreactionid
        ]);

        if(!$cvr)
            return $this->forwardToIndex();
        $cvr = $cvr[0];
        
        $cvr->setReaction(boolval($reaction));
        
        $userRepos = $entityManager->getRepository(User::class);
        $watchedCompany = $userRepos->find($cvr->getCompanyId());
        $watchedUser = $userRepos->find($cvr->getUserId());
        $watchedCompany->removeActiveCV($cvr->getId());
        $watchedUser->removeActiveCV($cvr->getId());

        $entityManager->persist($watchedCompany);
        $entityManager->persist($watchedUser);
        $entityManager->flush();

        return $this->forwardToIndex();
    }
}
