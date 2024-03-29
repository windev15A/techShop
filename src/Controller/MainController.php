<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\FabricantRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{

    /**
     * respoProducts
     *
     * @var ProductRepository
     */
    protected $repoProducts;

    /**
     * repoCategory
     *
     * @var CategoryRepository
     */
    protected $repoCategory;


    /**
     * repoFabricant
     *
     * @var FabricantRepository
     */
    protected $repoFabricant;



    /**
     * __construct
     *
     * @param  mixed $repo
     * @param  mixed $repoCat
     * @param  mixed $repoFabricant
     * @return void
     */
    public function __construct(
        ProductRepository $repo,
        CategoryRepository $repoCat,
        FabricantRepository $repoFab
    ) {
        $this->repoProducts = $repo;
        $this->repoCategory = $repoCat;
        $this->repoFabricant = $repoFab;
    }

    /**
     * index Affichage du home page
     *
     * @return Response
     */
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render(
            'home.html.twig',
            [
                "products" => $this->repoProducts->findAll(),
                'newProduct' => $this->repoProducts->getNewProduct(),
                "categories" => $this->repoCategory->findAll(),
                "fabricants" => $this->repoFabricant->findAll()
            ]
        );
    }

    /**
     * connexion
     *
     * @return Response
     */
    #[Route('/connexion', name: 'app_connexion')]
    public function connexion(): Response
    {
        return $this->render('security/connexion.html.twig');
    }


    /**
     * showProduct Afficher les details d'un produit
     *
     * @param  mixed $product
     * @return Response
     */
    #[Route('detail/{id}', name: 'app_show')]
    public function showProduct(int $id)
    {

        $product = $this->repoProducts->find($id);
        if (!$product) {
            return $this->error404();
        } else {
            return $this->render('showProduit.html.twig', [
                'product'  => $product
            ]);
        }
    }


    /**
     * error404 Page d'erreur 404
     *
     * @return Response
     */
    #[Route('/404', name: 'app_error')]    
    public function error404(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
    }

    #[Route('/500', name: 'app_error')]
    public function error500(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error500.html.twig');
    }



    /**
     * index Affichage du home page
     *
     * @return Response
     */
    #[Route('/home2', name: 'app_main2')]
    public function index_home(): Response
    {
        return $this->render(
            'home2.html.twig',
            [
                "products" => $this->repoProducts->findAll(),
                'newProduct' => $this->repoProducts->getNewProduct(),
                "categories" => $this->repoCategory->findAll(),
                "fabricants" => $this->repoFabricant->findAll()
            ]
        );
    }
}
