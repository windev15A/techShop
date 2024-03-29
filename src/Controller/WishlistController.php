<?php

namespace App\Controller;


use Psr\Log\LoggerInterface;
use App\Service\Wishlist\Wishlist;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class WishlistController extends AbstractController
{
    
    /**
     * logger
     *
     * @var LoggerInterface
     */
    protected mixed $logger;
    
    /**
     * __construct
     *
     * @param  LoggerInterface $logger
     * @return void
     */
    public function __construct(LoggerInterface $logger){
        $this->logger = $logger;
    }
    
    /**
     * index
     *
     * @param  Wishlist $wishlist
     * @return Response
     */
    #[Route('/wishlist', name: 'app_wishlist')]    
    public function index(Wishlist $wishlist): Response
    {
        return $this->render('cart/wishlist.html.twig',[
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
    public function add(int $id, Wishlist $wishlist): RedirectResponse
    {

        $wishlist->addToWishlist($id);

        $this->logger->info('Produit ajouté a la liste de souhaites');
        return $this->redirectToRoute('app_main');
    }
    
    
    
    /**
     * Supprimer un elemenet de la liste de souhaites
     *
     * @param int $id
     * @param  Wishlist $wishlist
     * @return RedirectResponse
     */
    #[Route('wishlist/delete/{id}', name:'Wishlist_delete')]
    public function delete(int $id, Wishlist $wishlist): RedirectResponse
    {
        $wishlist->deleteToWishlist($id);

        $this->addFlash('success', 'Le produit est retiré de la list de souhaits :(');
        $this->logger->info('Produit retiré de la liste de souhaites');

        return $this->redirectToRoute('app_main');
    }

}