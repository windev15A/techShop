<?php

namespace App\Controller\Admin;


use DateTime;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]

class CategoryController extends AbstractController
{
    /**
     * index list des catégories
     *
     * @param CategoryRepository $repo
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/categories', name: 'app_categories')]
    public function index(CategoryRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {
        $categories = $paginator->paginate(
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
            'admin/category/index.html.twig',
            [
                'categories' => $categories
            ]
        );
    }


    /**
     * newCategory Afficher formulaire du catgorie
     * Ajouter un nouveau categorie
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/category/new', name: 'app_new_category')]
    public function newCatgory(Request $request, EntityManagerInterface $manager): Response
    {

        $category = new Category();
        $formCategory = $this->createForm(CategoryType::class);

        $formCategory->handleRequest($request);

        if ($formCategory->isSubmitted() && $formCategory->isValid()) {

            $category->setCreatedAt(new DateTime("now"));
            $category = $formCategory->getData();
            $file = $formCategory->get('image')->getData();

            if ($file) {

                $nameImage = Date('now') . "-" . uniqid() . "." . $file->guessExtension();

                $category->setImage($nameImage);

                $file->move($this->getParameter('image_category'), $nameImage);
            }

            $category->setCreatedAt(new DateTime("now"));
            $manager->persist($category);
            $manager->flush();

            $this->addFlash('success', 'La catégorie ' . $category->getLibelle() . ' a été ajouter avec succès');

            return $this->redirectToRoute('app_categories');
        }
        return $this->render(
            'admin/category/new.html.twig',
            [
                'form' => $formCategory->createView()
            ]
        );
    }


    /**
     * updateCategory
     *
     * @param Category $category
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/category/update/{id}', name: 'app_update_category')]
    public function updateCategory(Category $category, Request $request, EntityManagerInterface $manager): Response
    {


        $ancienneImage = $category->getImage();

        $formCategory = $this->createForm(CategoryType::class, $category);
        $formCategory->handleRequest($request);

        if ($formCategory->isSubmitted() && $formCategory->isValid()) {


            $imageFile = $formCategory->get('image')->getData();

            if ($imageFile) {
                if ($ancienneImage) {
                    $pathImage = $this->getParameter('image_category') . "/" . $ancienneImage;
                    if (file_exists($pathImage)) {
                        unlink($pathImage);
                    }
                }
                $nameImage = Date('now') . "-" . uniqid() . "." . $imageFile->guessExtension();

                $category->setImage($nameImage);

                $imageFile->move($this->getParameter('image_category'), $nameImage);
            } else {
                $category->setImage($ancienneImage);
            }

            $category->setUpdatedAt(new DateTime("now"));
            $manager->persist($category);
            $manager->flush();

            $this->addFlash('success', 'La catégorie ' . $category->getLibelle() . ' a été modifié avec succès');
            return $this->redirectToRoute('app_categories');
        }

        return $this->render('admin/category/update.html.twig', [
            'form' => $formCategory->createView()
        ]);
    }



    /**
     * deleteCategory
     *
     * @param  Category $category
     * @param  EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/category/delete/{id}', name: 'app_delete_category')]
    public function deleteCategory(Category $category, EntityManagerInterface $manager): Response
    {
        $idProduit = $category->getId();
        $image_category = $category->getImage();
        if ($image_category) {
            $pathImage = $this->getParameter('image_category') . '/' . $image_category;
            if (file_exists($pathImage)) {
                unlink($pathImage);
            }
        }
        $manager->remove($category);
        $manager->flush();


        $this->addFlash('success', "La catégorie n° $idProduit a été supprimer avec avec succès");
        return $this->redirectToRoute('app_categories');
    }
}
