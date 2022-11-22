<?php

namespace App\Controller;

use App\Repository\BoutiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoutiqueController extends AbstractController
{

    /**
     * rep
     *
     * @var BoutiqueRepository
     */
    protected $rep;

    public function __construct(BoutiqueRepository $rep)
    {
        $this->rep = $rep;
    }




    /**
     * index
     *
     * @return Response
     */
    #[Route('/boutique', name: 'app_boutique')]    
    public function index(): Response
    {
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController',
        ]);
    }


    /**
     * searchBoutique 
     *
     * @param  Request $request
     * @return void
     */
    #[Route('boutique/search', name: 'app_search_boutique')]
    public function searchBoutique(Request $request)
    {

        $boutiques = $this->rep
                        ->findByName(
                            $request->getContent()
                        );

        return $this->json($boutiques, 200);
    }


    /**
     * searchProxyBoutiques : boutique de proximité 
     *
     * @param  Request $request
     * @return JsonResponse
     */

    #[Route('serachboutiques', name: 'app_boutique_poxy')]    
    public function searchProxyBoutiques(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        $boutique = $this->rep->find($data->id);
        
        $boutiques = $this->rep->findProxyBoutique($boutique, $data->distance);

        return $this->json(['boutiques' => $boutiques], 200);
        }
}
