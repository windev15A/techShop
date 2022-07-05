<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    
    /**
     * index
     *
     * @return void
     */

    #[Route('contact', name: 'app_contact')]    
    public function index(){
        return $this->render('contact.html.twig');
    }


}