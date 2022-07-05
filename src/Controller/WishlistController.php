<?php

namespace App\Controller;


use Psr\Log\LoggerInterface;
use App\Service\Wishlist\Wishlist;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PHPUnit\TextUI\XmlConfiguration\Logging\Logging;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class WishlistController extends AbstractController
{
    
    /**
     * logger
     *
     * @var LoggerInterface
     */
    protected $logger;
    
    /**
     * __construct
     *
     * @param  mixed $logger
     * @return void
     */
    public function __construct(LoggerInterface $logger){
        $this->logger = $logger;
    }




    
    /**
     * index
     *
     * @param  mixed $wishlist
     * @return Response
     */
    #[Route('/wishlist', name: 'app_wishlist')]    
    public function index(Wishlist $wishlist): Response
    {
        return $this->render('cart/index.html.twig',[
            'products' => $wishlist->getFullWishlist(),
        ]);
    }


    /**
     * add
     * 
     * Ajouter un produit a la list de souhaits
     *
     * @param  int $id
     * @param  Wishlist $wishlist
     * @return RedirectResponse
     */
    #[Route('wishlist/add/{id}', name:'wishlist_add')]    
    public function add(int $id, Wishlist $wishlist){

        $wishlist->addToWishlist($id);

        $this->logger->info('Produit ajouté a la liste des souhaite');
        return $this->redirectToRoute('app_main');
    }
    
    
    
    /**
     * delete
     *
     * @param  int $id
     * @param  Wishlist $wishlist
     * @return RedirectResponse
     */
    #[Route('wishlist/delete/{id}', name:'Wishlist_delete')]
    public function delete($id, Wishlist $wishlist)
    {
        $wishlist->deleteToWishlist($id);

        $this->addFlash('success', 'Le produit est retiré de la list de souhaits :(');

        return $this->redirectToRoute('app_main');
    }

}