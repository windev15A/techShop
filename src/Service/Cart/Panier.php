<?php


namespace App\Service\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier
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
     * addToCart 
     * 
     * Ajouter le produit au panier
     *
     * @param  int $id
     * @return void
     */
    public function addToCart(int $id)
    {

        $panier = $this->session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $this->session->set('panier', $panier);
    }

    /**
     * updateCart
     * 
     * Modifier la quantité du panier
     *
     * @param  int $id
     * @param  int $qty
     * @return void
     */

    public function updateCart(int $id, int $qty)
    {

        $panier = $this->session->get('panier', []);

        if (!empty($panier)) {
            $panier[$id] = $qty;
        }

        $this->session->set('panier', $panier);
    }


    /**
     * deleteToCart
     * 
     * Supprimer un produit du panier
     *
     * @param  int $id
     * @return void
     */

    public function deleteToCart(int $id)
    {

        $panier = $this->session->get('panier', []);

        if (!empty($panier)) {
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }


    /**
     * getFullCart
     *
     * Obtenir le contenu du panier 
     * 
     * @return array
     */
    public function getFullCart(): array
    {
        $panier = $this->session->get('panier', []);

        $dataPanier = [];

        foreach ($panier as $id => $qty) {

            $dataPanier[] = [
                'product' => $this->repoProduct->find($id),
                'qty' => $qty
            ];
        }

        return $dataPanier;
    }

    
    /**
     * getTotal
     * 
     * Calculer le total du panier
     *
     * @return float
     */
    public function getTotal()
    {
        $total = 0;

        $dataPanier = $this->getFullCart();

        foreach ($dataPanier as $item) {
            $total += $item['product']->getPrix() * $item['qty'];
        }

        return $total;

    }
}
