<?php

namespace App\Controller\Admin;

use App\Entity\Boutique;
use App\Repository\BoutiqueRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BoutiqueController extends AbstractController
{


    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->em = $entityManager;
    }


    /**
     * index
     *
     * @param  mixed $repo
     * @param  mixed $request
     * @param  mixed $paginator
     * @return Response
     */

    #[Route('/admin/boutique', name: 'app_admin_boutique')]
    public function index(BoutiqueRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {

        $boutiques = $paginator->paginate(
            $repo->findBy(
                array(),
                [
                    'nom' => 'desc'
                ]
            ),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'admin/boutique/index.html.twig',
            [
                'boutiques' => $boutiques
            ]
        );
    }



    /**
     * newBoutique
     *
     * @return Response
     */
    #[Route('boutique/new', name: 'app_new_boutique')]
    public function newBoutique(): Response
    {
        return $this->render('admin/boutique/new.html.twig');
    }


    #[Route('boutique/store', name: 'app_store_boutique')]
    public function store(Request $request)
    {
        try {
            
            $boutique = new Boutique();
            $boutique->setNom($request->request->get("nom"));
            $boutique->setAdresse($request->request->get("adresse"));
            $boutique->setLongitude($request->request->get("longitude"));
            $boutique->setLatitude($request->request->get("latitude"));
            $boutique->setCreatedAt(new DateTime('now'));
            $this->em->persist($boutique);
            $this->em->flush();
            $this->addFlash('success', 'La boutique ' . $boutique->getNom() . ' a été ajouter avec succès');
            
            return $this->redirectToRoute('app_admin_boutique');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            $this->addFlash('error', "Erreur lors de l'ajout de cette boutique");
            return $this->redirectToRoute('app_admin_boutique');
            
        }
    }


    #[Route('boutique/show/{id}', name:'app_show_boutique')]
    public function show(Boutique $boutique)
    {
        return $this->render(
            'admin/boutique/show.html.twig',
            [
                'boutique' => $boutique
            ]
        );
    }


    #[Route('boutique/edit/{id}', name: 'app_edit_boutique')]
    public function edit(Boutique $boutique, Request $request)
    {
        try {
            
            $boutique->setNom($request->request->get("nom"));
            $boutique->setAdresse($request->request->get("adresse"));
            $boutique->setLongitude($request->request->get("longitude"));
            $boutique->setLatitude($request->request->get("latitude"));
            // $boutique->setCreatedAt(new DateTime('now'));
            $this->em->persist($boutique);
            $this->em->flush();
            $this->addFlash('success', 'La boutique ' . $boutique->getNom() . ' a été modifier avec succès');
            
            return $this->redirectToRoute('app_admin_boutique');
        } catch (\Throwable $th) {
            $this->addFlash('error', "Erreur lors de la modification de cette boutique");
            return $this->redirectToRoute('app_admin_boutique');
            
        }
    }

    #[Route('boutique/delete/{id}', name:'app_delete_boutique')]
    public function delete(Boutique $boutique)
    {

        $this->em->remove($boutique);
        $this->em->flush();

        $this->addFlash('success', 'La boutique  a été supprimer avec succès');
            
        return $this->redirectToRoute('app_admin_boutique');
    }
}
