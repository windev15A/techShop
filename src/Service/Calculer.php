<?php


namespace App\Service;

use App\Service\Cart\Panier;
use Symfony\Component\HttpFoundation\RequestStack;

class Calculer
{

    /**
     * session
     *
     * @var SessionInterface
     */
    protected $session;

    public function __construct(RequestStack $session)
    {
        $this->session = $session->getSession();
    }

        
    /**
     * totalAPayer
     *
     * @param  Panier $panier
     * @return float 
     */
    public function totalAPayer(Panier $panier): float
    {
        $total = $panier->getTotal();
        $reduction = $this->session->get('reduction', []);
        $totalReduc = 0;

        if(!empty($reduction)){
            
            $totalReduc = ($total * array_values($reduction)[0])/ 100;
        }

        $newTotal = $total - $totalReduc;

        // Calcule TVA 
        $TVA = $newTotal * 0.20 ;
        
        $TTC = $newTotal + $TVA;

        return $TTC;


    }
}
