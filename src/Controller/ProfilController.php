<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * index
     *
     * @return Response
     */
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig');
    }


    /**
     * showInfo
     *
     * @return void
     */
    #[Route('mesinfos/{id}', name: 'app_show_info')]
    public function showInfo(User $user, Request $request, EntityManagerInterface $manager): Response
    {
        
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user->setUpdatedAt(new DateTime("now"));
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Vos informations personnel ont été modifié avec succès');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render(
            'profil/show.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
