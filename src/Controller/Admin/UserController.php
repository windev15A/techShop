<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]

class UserController extends AbstractController
{
    /**
     * index
     *
     * @param mixed $repo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/users', name: 'app_users')]    
    public function index(UserRepository $repo, PaginatorInterface $paginator,Request $request): Response
    {

        $users= $paginator->paginate(
            $repo->findBy(
                [],
                ['created_at' => 'desc']
            ),
            $request->query->getInt('page', 1),
            
            10
        );

        return $this->render('admin/user/index.html.twig',[
            'users' => $users
        ]);
    }
}
