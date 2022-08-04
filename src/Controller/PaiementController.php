<?php

namespace App\Controller;

use DateTime;
use Stripe\Stripe;
use App\Service\Calculer;
use Stripe\PaymentIntent;
use App\Service\Cart\Panier;
use Psr\Log\LoggerInterface;
use App\Entity\HistoireCommande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Throwable;

class PaiementController extends AbstractController
{

    /**
     * logger
     *
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * session
     *
     * @var SessionInterface
     */
    protected SessionInterface $session;


    /**
     * __construct
     *
     * @param RequestStack $session
     * @param mixed $logger
     */
    public function __construct(RequestStack $session, LoggerInterface $logger)
    {
        $this->session = $session->getSession();
        $this->logger = $logger;
    }

    /**
     * index
     *
     * @param Panier $panier
     * @param Calculer $calculer
     * @return Response
     */
    #[Route('/paiement', name: 'app_paiement')]
    public function index(Panier $panier, Calculer $calculer): Response
    {
        try {
            $total = $calculer->totalAPayer($panier);
            Stripe::setApiKey('sk_test_E8BbOkpkiejWMiPjLXenAz5J00buAeYztX');

            $intent = PaymentIntent::create([
                'amount' => round($total * 100),
                'currency' => 'eur'

            ]);
            return $this->render('paiement/index.html.twig', [
                'clientSecret' => $intent->client_secret,
                'total' => $total
            ]);
        } catch (Throwable $th) {
            $this->logger->error($th->getMessage());
            return $this->redirectToRoute('app_main');
        }
    }


    /**
     * successUrl
     *
     * @param Panier $panier
     * @param mixed $em
     * @param mixed $calculer
     * @return Response
     */
    #[Route('/merci', name: 'app_thanks')]    
    public function successUrl(
        Panier $panier,
        EntityManagerInterface $em,
        Calculer $calculer): Response
    {

        if (count($panier->getFullCart())) {
            try {
                //code...
                $MaCommande = $panier->getFullCart();
                $comande = new HistoireCommande();
                $comande->setProduits(serialize($MaCommande));
                $comande->setMontant($calculer->totalAPayer($panier));
                $comande->setDateCreation(new DateTime("now"));
                $comande->setUser($this->getUser());

                $em->persist($comande);
                $em->flush();
                $this->logger->info("Nouvelle commande enregistré .");

                $this->session->remove('panier');
                return $this->render('paiement/merci.html.twig');
            } catch (Throwable $th) {
                $this->logger->error($th->getMessage());
            }
        } else {
            return $this->redirectToRoute('app_main');
        }
    }
}
