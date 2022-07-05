<?php


namespace App\Service\Wishlist;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class Wishlist
{
    /**
     * session
     *
     * @var SessionInterface
     */
    protected $session;

    /**
     * repoProduct
     *
     * @var ProductRepository
     */
    protected $repoProduct;



    public function __construct(RequestStack $session, ProductRepository $repoProduct)
    {
        $this->session = $session->getSession();
        $this->repoProduct = $repoProduct;
    }


    /**
     * addToWishlist 
     * 
     * Ajouter le produit au wishlist
     *
     * @param  int $id
     * @return void
     */
    public function addToWishlist(int $id)
    {

        $wishlist = $this->session->get('wishlist', []);

        if(!in_array($id, $wishlist)){
            array_push($wishlist, $id);
        }else{
            $value = array_search($id, $wishlist, true);
            if($value !== false){
                unset($wishlist[$value]);
            }
        }
        $this->session->set('wishlist', $wishlist);
    }

    


    /**
     * deleteToWishlist
     * 
     * Supprimer un produit du wishlist
     *
     * @param  int $id
     * @return void
     */

    public function deleteToWishlist(int $id)
    {

        $wishlist = $this->session->get('wishlist', []);

        if (!empty($wishlist)) {
            unset($wishlist[$id]);
        }

        $this->session->set('wishlist', $wishlist);
    }


    /**
     * getFullWishlist
     *
     * Obtenir le contenu du wishlist 
     * 
     * @return array
     */
    public function getFullWishlist(): array
    {
        $wishlist = $this->session->get('wishlist', []);
        

        $datawishlist = [];

        foreach ($wishlist as $id) {

            array_push($datawishlist,
            $this->repoProduct->find($id))
        ;
        }
        return $datawishlist;
    }

    
   
}