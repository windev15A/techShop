<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Address;
use Throwable;

class ContactController extends AbstractController
{

    /**
     * index
     *
     * @return Response
     */

    #[Route('contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact.html.twig');
    }


    /**
     * sendEmail
     *
     * @param mixed $mailer
     * @param mixed $request
     * @return Response|void
     */
    #[Route('send', name: 'app_sendEmail')]
    public function sendEmail(MailerInterface $mailer, Request $request)
    {
        try {
            $email = (new Email())
                ->from(new Address($request->get('email')))
                ->to('admin@admin.fr')

                ->subject($request->get('object'))
                ->text($request->get('message'));

            $mailer->send($email);
            $this->addFlash('success', 'Email envoyé avec success');
            return $this->render('contact.html.twig');
        } catch (Throwable $th) {
            $this->addFlash('error', "Impossible d'envoyer l'email");
            return $this->render('contact.html.twig');
            
        }
    }
}
