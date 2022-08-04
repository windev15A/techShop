<?php

namespace App\Controller\Admin;


use DateTime;
use App\Entity\Promo;
use App\Form\PromoType;
use App\Repository\PromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]

class PromoController extends AbstractController
{
    /**
     * index list des catégories
     *
     * @param PromoRepository $repo
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/promos', name: 'app_promos')]
    public function index(PromoRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {
        $promos = $paginator->paginate(
            $repo->findBy(
                array(),
                [
                    'created_at' => 'desc'
                ]
            ),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'admin/promo/index.html.twig',
            [
                'promos' => $promos
            ]
        );
    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/promo/new', name: 'app_new_promo')]
    public function newPromo(Request $request, EntityManagerInterface $manager): Response
    {

        $promo = new promo();
        $formpromo = $this->createForm(promoType::class);

        $formpromo->handleRequest($request);

        if ($formpromo->isSubmitted() && $formpromo->isValid()) {

            $promo = $formpromo->getData();
            $promo->setCreatedAt(new DateTime("now"));
            $manager->persist($promo);
            $manager->flush();

            $this->addFlash('success', 'Le code promo a été ajouter avec succès');

            return $this->redirectToRoute('app_promos');
        }


        return $this->render(
            'admin/promo/new.html.twig',
            [
                'form' => $formpromo->createView()
            ]
        );
    }


    /**
     * updatepromo
     *
     * @param Promo $promo
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/promo/update/{id}', name: 'app_update_promo')]
    public function updatepromo(Promo $promo, Request $request, EntityManagerInterface $manager): Response
    {

        $formpromo = $this->createForm(PromoType::class, $promo);
        $formpromo->handleRequest($request);

        if ($formpromo->isSubmitted() && $formpromo->isValid()) {

            $manager->persist($promo);
            $manager->flush();

            $this->addFlash('success', 'Le code promo a été modifié avec succès');
            return $this->redirectToRoute('app_promos');
        }

        return $this->render('admin/promo/update.html.twig', [
            'form' => $formpromo->createView()
        ]);
    }



    /**
     * deletepromo
     *
     * @param  Promo $promo
     * @param  EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/promo/delete/{id}', name: 'app_delete_promo')]
    public function deletepromo(Promo $promo, EntityManagerInterface $manager)
    {
        $idPromo = $promo->getCodePromo();
        $manager->remove($promo);
        $manager->flush();


        $this->addFlash('success', "Le code promo n° $idPromo a été supprimer avec succès");
        return $this->redirectToRoute('app_promos');
    }
}
