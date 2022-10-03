<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\HistoireCommandeRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]

class ProductController extends AbstractController
{
    /**
     * index list des produit
     *
     * @param ProductRepository $repo
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/products', name: 'app_products')]
    public function index(ProductRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {
        $products = $paginator->paginate(
            $repo->findBy(
                [],
                ['created_at' => 'desc']
            ),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render(
            'admin/product/index.html.twig',
            [
                'products' => $products
            ]
        );
    }



    /**
     * newProduct Afficher formulaire du produit 
     * Ajouter un nouveau produit
     *
     * @param  mixed $request
     * @param  mixed $manager
     * @return Response
     */
    #[Route('/product/new', name: 'app_new_product')]
    public function newProduct(Request $request, EntityManagerInterface $manager): Response
    {

        $produit = new Product();
        $formProduit = $this->createForm(ProductType::class);

        $formProduit->handleRequest($request);

        if ($formProduit->isSubmitted() && $formProduit->isValid()) {

            $produit->setCreatedAt(new DateTime("now"));
            $produit = $formProduit->getData();
            $file = $formProduit->get('image')->getData();

            if ($file) {

                $nameImage = Date('now') . "-" . uniqid() . "." . $file->guessExtension();

                $produit->setImage($nameImage);

                $file->move($this->getParameter('image_produit'), $nameImage);
            }
            $produit->setCreatedAt(new DateTime("now"));

            $manager->persist($produit);
            $manager->flush();


            $this->addFlash('success', 'Le produit ' . $produit->getLibelle() . ' a été ajouter avec succès');

            return $this->redirectToRoute('app_products');
        }


        return $this->render(
            'admin/product/new.html.twig',
            [
                'form' => $formProduit->createView()
            ]
        );
    }


    /**
     * updateProduct
     *
     * @param Product $produit
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('product/update/{id}', name: 'app_update_product')]
    public function updateProduct(Product $produit, Request $request, EntityManagerInterface $manager): Response
    {

        $ancienneImage = $produit->getImage();

        $formProduit = $this->createForm(ProductType::class, $produit);
        $formProduit->handleRequest($request);

        if ($formProduit->isSubmitted() && $formProduit->isValid()) {


            $imageFile = $formProduit->get('image')->getData();

            if ($imageFile) {
                if ($ancienneImage) {
                    $pathImage = $this->getParameter('image_produit') . "/" . $ancienneImage;
                    if (file_exists($pathImage)) {
                        unlink($pathImage);
                    }
                }

                $nameImage = Date('now') . "-" . uniqid() . "." . $imageFile->guessExtension();

                $produit->setImage($nameImage);

                $imageFile->move($this->getParameter('image_produit'), $nameImage);
            } else {
                $produit->setImage($ancienneImage);
            }
            $produit->setUpdatedAt(new DateTime("now"));

            $manager->persist($produit);
            $manager->flush();

            $this->addFlash('success', 'Le produit ' . $produit->getLibelle() . ' a été modifié avec succès');
            return $this->redirectToRoute('app_products');
        }

        return $this->render('admin/product/update.html.twig', [
            'form' => $formProduit->createView()
        ]);
    }



    /**
     * deleteProduct
     *
     * @param  Product $produit
     * @param  EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/product/delete/{id}', name: 'app_delete_product')]
    public function deleteProduct(Product $produit, EntityManagerInterface $manager)
    {
        $idProduit = $produit->getId();
        $image_produit = $produit->getImage();
        if ($image_produit) {
            $pathImage = $this->getParameter('image_produit') . '/' . $image_produit;
            if (file_exists($pathImage)) {
                unlink($pathImage);
            }
        }
        $manager->remove($produit);
        $manager->flush();


        $this->addFlash('success', "Le produit n° $idProduit a été supprimer avec avec succès");
        return $this->redirectToRoute('app_products');
    }



    
}
