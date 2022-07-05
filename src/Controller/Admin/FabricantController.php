<?php

namespace App\Controller\Admin;


use DateTime;
use App\Entity\Fabricant;
use App\Form\FabricantType;
use App\Repository\FabricantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]

class FabricantController extends AbstractController
{

    #[Route('/fabricants', name: 'app_fabricants')]
    /**
     * index
     *
     * @param  FabricantRepository $repo
     * @param  Request $request
     * @param  PaginatorInterface $paginator
     * @return Response
     */
    public function index(FabricantRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {

        $fabricants = $paginator->paginate(
            $repo->findBy(
                array(),
                ['created_at' => 'desc']
            ),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render(
            'admin/fabricant/index.html.twig',
            [
                'fabricants' => $fabricants
            ]
        );
    }



    /**
     * newFabricant Afficher formulaire du fabricant
     * Ajouter un nouveau fabricant
     *
     * @param  Request $request
     * @param  EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/fabricant/new', name: 'app_new_fabricant')]
    public function newFabricant(Request $request, EntityManagerInterface $manager)
    {

        $fabricant = new Fabricant();
        $formFabricant = $this->createForm(FabricantType::class);

        $formFabricant->handleRequest($request);

        if ($formFabricant->isSubmitted() && $formFabricant->isValid()) {

            $fabricant = $formFabricant->getData();
            $file = $formFabricant->get('image')->getData();

            if ($file) {

                $nameImage = Date('now') . "-" . uniqid() . "." . $file->guessExtension();
                $fabricant->setImage($nameImage);
                $file->move($this->getParameter('image_fabricant'), $nameImage);
            }

            $fabricant->setCreatedAt(new DateTime("now"));
            $manager->persist($fabricant);
            $manager->flush();


            $this->addFlash('success', 'La catégorie ' . $fabricant->getNom() . ' a été ajouter avec succès');

            return $this->redirectToRoute('app_fabricants');
        }


        return $this->render(
            'admin/Fabricant/new.html.twig',
            [
                'form' => $formFabricant->createView()
            ]
        );
    }




    /**
     * updateFabricant
     *
     * @param  Fabricant $fabricant
     * @param  Request $request
     * @param  EntityManagerInterface $em
     * @return Response
     */
    #[Route('/Fabricant/update/{id}', name: 'app_update_fabricant')]
    public function updateFabricant(Fabricant $fabricant, Request $request, EntityManagerInterface $manager): Response
    {

        $ancienneImage = $fabricant->getImage();
        $formFabricant = $this->createForm(FabricantType::class, $fabricant);
        $formFabricant->handleRequest($request);

        if ($formFabricant->isSubmitted() && $formFabricant->isValid()) {

            $imageFile = $formFabricant->get('image')->getData();
            if ($imageFile) {
                if ($ancienneImage) {
                    $pathImage = $this->getParameter('image_fabricant') . "/" . $ancienneImage;
                    if (file_exists($pathImage)) {
                        unlink($pathImage);
                    }
                }
                $nameImage = Date('now') . "-" . uniqid() . "." . $imageFile->guessExtension();
                $fabricant->setImage($nameImage);
                $imageFile->move($this->getParameter('image_fabricant'), $nameImage);
            } else {
                $fabricant->setImage($ancienneImage);
            }

            $fabricant->setUpdatedAt(new DateTime("now"));
            $manager->persist($fabricant);
            $manager->flush();

            $this->addFlash('success', 'La catégorie ' . $fabricant->getNom() . ' a été modifié avec succès');
            return $this->redirectToRoute('app_fabricants');
        }

        return $this->render('admin/Fabricant/update.html.twig', [
            'form' => $formFabricant->createView()
        ]);
    }



    /**
     * deleteFabricant
     *
     * @param  Fabricant $fabricant
     * @param  EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/fabricant/delete/{id}', name: 'app_delete_fabricant')]
    public function deleteFabricant(Fabricant $fabricant, EntityManagerInterface $manager)
    {
        $idfabricant = $fabricant->getNom();
        $image_fabricant = $fabricant->getImage();
        if ($image_fabricant) {
            $pathImage = $this->getParameter('image_fabricant') . '/' . $image_fabricant;
            if (file_exists($pathImage)) {
                unlink($pathImage);
            }
        }
        $manager->remove($fabricant);
        $manager->flush();


        $this->addFlash('success', "La categorie  $idfabricant a été supprimer avec avec succès");
        return $this->redirectToRoute('app_fabricants');
    }
}
