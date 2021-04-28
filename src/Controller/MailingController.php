<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailingController extends AbstractController
{
    /**
     * @Route("/mailing", name="mailing")
     */
    public function index(): Response
    {
        return $this->render('mailing/index.html.twig', [
            'controller_name' => 'MailingController',
        ]);
    }
}
