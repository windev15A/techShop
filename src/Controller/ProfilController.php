<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\HistoireCommandeRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * @var HistoireCommandeRepository
     */
    protected HistoireCommandeRepository $repo;


    /**
     * __construct
     *
     * @param  HistoireCommandeRepository $rep
     * @return void
     */
    public function __construct(HistoireCommandeRepository $rep)
    {
        $this->repo =  $rep;
    }


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
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('mesinfos/{id}', name: 'app_show_info')]
    public function showInfo(User $user, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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


    /**
     * @return Response
     */
    #[Route('historique', name: 'hitorique_commande')]
    public function historiqueCommande(): Response
    {

        $commandes = $this->repo->findBy([
            'user' => $this->getUser()
        ]);

        $produits = [];

        foreach ($commandes as $key => $produit) {
            $produits[$key] = [
                "numero" => $produit->getId(),
                "dateFacture" => $produit->getDateCreation(),
                "montant" => $produit->getMontant(),
                "contenus" => unserialize($produit->getProduits())
            ];
        }

        return $this->render('profil/historiqueCommande.html.twig', [
            "commandes" => $produits
        ]);
    }




    
    /**
     * deleteUser Supprimer un compte utilisateur
     *
     * @param  mixed $request
     * @param  mixed $user
     * @param  mixed $userRepository
     * @return Response
     */
    #[Route("delete/{id}",name:"delete_user")]    
    public function deleteUser(Request $request, User $user, UserRepository $userRepository ): Response
    {
        if($this->isCsrfTokenValid('delete-user', $request->request->get('token'))){
            $this->container->get('security.token_storage')->setToken(null);
            $userRepository->remove($user, true);
            $this->addFlash('success', 'Votre compte a été supprimer avec success');
            return $this->redirectToRoute('app_main');
        }else{
            $this->addFlash('error', 'Impossible de supprimer ce compte');
            return $this->redirectToRoute('app_main');
        }
    }

}
