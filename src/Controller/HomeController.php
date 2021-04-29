<?php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
    * @Route("/", name="home")
    */
    public function index(): Response
    {

    return $this->render('index.html.twig', [

    ]);
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin(): Response
    {

        return $this->render('admin.html.twig', [

        ]);


    }
}