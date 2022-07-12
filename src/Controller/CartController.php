<?php

namespace App\Controller;

use DateTime;
use Throwable;
use App\Service\Cart\Panier;
use Psr\Log\LoggerInterface;
use App\Repository\PromoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{


    /**
     * logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * session
     *
     * @var SessionInterface
     */
    protected $session;




    /**
     * __construct
     *
     * @param  mixed $logger
     * @return void
     */
    public function __construct(RequestStack $session, LoggerInterface $logger)
    {
        $this->session = $session->getSession();
        $this->logger = $logger;
    }




    #[Route('/panier', name: 'app_cart')]
    public function index(Panier $panier): Response
    {
        return $this->render('cart/index.html.twig', [
            'products' => $panier->getFullCart(),
            'total' => $panier->getTotal()

        ]);
    }


    /**
     * add
     * 
     * Ajouter un produit au panier
     *
     * @param  int $id
     * @param  Panier $panier
     * @return RedirectResponse
     */
    #[Route('panier/add/{id}', name: 'panier_add')]
    public function add(int $id, Panier $panier)
    {

        try {
            $panier->addToCart($id);
            $this->addFlash('success', 'Le produit est dans le panier !');
        } catch (Throwable $th) {
            $this->logger->error($th->getMessage());
        }

        return $this->redirectToRoute('app_main');
    }

    /**
     * update
     *
     * @param  int $id
     * @param  int $qty
     * @param  Panier $panier
     * @return RedirectResponse
     */
    #[Route('panier/update/{id}/{qty}', name: 'panier_update')]
    public function update(int $id, int $qty, Panier $panier, Request $request)
    {

        try {

            $panier->updateCart($id, $qty);

            $this->addFlash('success', 'La quantité du produit est passé a ' . $qty);
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
        }

        $route =  $request->headers->get('referer');
        // Une redirection vers la route 
        return  $this->redirect($route);
    }

    /**
     * delete
     *
     * @param  int $id
     * @param  Panier $panier
     * @return RedirectResponse
     */
    #[Route('panier/delete/{id}', name: 'panier_delete')]
    public function delete($id, Panier $panier)
    {
        try {

            $panier->deleteToCart($id);

            $this->addFlash('success', 'Le produit est retiré du panier :(');
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
        }

        return $this->redirectToRoute('app_main');
    }



    /**
     * addCodePromo
     *
     * @param  string $codePromo
     * @return void
     */
    #[Route('panier/promo/{codePromo}', name: 'panier_add_code')]
    public function addCodePromo(string $codePromo, PromoRepository $promoRepository, Request $request)
    {
        $promo = $promoRepository->findOneBycode($codePromo);

        $reduction = $this->session->get('reduction', []);
        
        if ($promo) {
            if(empty($reduction)){
                $reduction[$promo->getCodePromo()] = $promo->getTauxPromo();
            }

            $this->session->set('reduction', $reduction);
        } else {
            dd('non disponible');
        }
        
        $route =  $request->headers->get('referer');
        // Une redirection vers la route 
        return  $this->redirect($route);
    }

    /**
     * addCodePromo
     *
     * @param  string $codePromo
     * @return void
     */
    #[Route('koko', name: 'panier_delete_code)')]
    public function DeleteCodePromo(string $codePromo)
    {
        dd('lllll');
    }
}
