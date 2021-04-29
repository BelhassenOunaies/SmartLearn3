<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Coupon;
use App\Entity\Course;
use App\Form\AchatType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AchatController extends AbstractController
{
    /**
     * @Route("/achat", name="achat")
     */
    public function index(): Response
    {
        $dat = $this->getDoctrine()->getRepository(Achat::class);
        $achats = $dat->findAll();
        return $this->render('achat/index.html.twig', [
            'achats' => $achats,
        ]);
    }

    /**
     * @Route("/cart", name="cart")
     */
    public function display_cart(): Response
    {
        $c_dat = $this->getDoctrine()->getRepository(Course::class);
        $dat = $this->getDoctrine()->getRepository(Achat::class);
        $achats = $dat->findBy(['achatFinalise' => 0]);
        $achats_cours = [];
        $index = 0;
        $totalPrice = 0;
        foreach ($achats as $a)
        {
            $achats_cours[$index] = $c_dat->find($a->getCourseId());
            $totalPrice += (float)$achats_cours[$index]->getPrice();
            $index++;
        }
        return $this->render('achat/cart.html.twig', [
            'achats_cours' =>  $achats_cours,
            'achats' =>  $achats,
            'totalPrice' => $totalPrice
        ]);
    }
    /**
     * @Route("/owned_courses", name="owned_courses")
     */
    public function display_owned_courses(): Response
    {
        $c_dat = $this->getDoctrine()->getRepository(Course::class);
        $dat = $this->getDoctrine()->getRepository(Achat::class);
        $achats = $dat->findBy(['achatFinalise' => 1]);
        $achats_cours = [];
        $index = 0;
        foreach ($achats as $a)
        {
            $achats_cours[$index] = $c_dat->find($a->getCourseId());
            $index++;
        }

        return $this->render('achat/owned_courses.html.twig', [
            'achats_cours' =>  $achats_cours,
            'achats' =>  $achats,
        ]);
    }

    /**
     * @Route("/achat/appliquer_coupon")
     */
    public function appliquer_coupon(Request $request)
    {

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $code = $request->get("textId");
            $coupon_dat = $this->getDoctrine()->getRepository(Coupon::class);
            $coupon = $coupon_dat->findOneBy(['textId' => $code,'used' => 0]);

            if($coupon != null) {
                $coupon->setUsed(1);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($coupon);
                $manager->flush();

                $jsonData = array();
                $idx = 0;

                $temp = array(
                    'id' => $coupon->getId(),
                    'textId' => $coupon->getTextId(),
                    'percentage' => $coupon->getPercentage()
                );

                $jsonData[$idx++] = $temp;
            }
            else
            {
                $jsonData = array();
            }

            return new JsonResponse($jsonData);
        } else {
            return $this->render('student/ajax.html.twig');
        }
    }

    /**
     * @Route("/cart/validate", name="validate_achats")
     */
    public function validate_achats(): Response
    {
        $dat = $this->getDoctrine()->getRepository(Achat::class);
        $achats = $dat->findBy(['achatFinalise' => 0]);
         $achats_cours = [];
        foreach ($achats as $a)
        {
            $a->setAchatFinalise(1);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($a);
            $manager->flush();
        }
        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/cart/delete_all_achats", name="delete_all_achats")
     */
    public function delete_all_achats(): Response
    {
        $dat = $this->getDoctrine()->getRepository(Achat::class);
        $achats = $dat->findBy(['achatFinalise' => 0]);
        $achats_cours = [];
        foreach ($achats as $a)
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($a);
            $manager->flush();
        }
        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/achat/delete/{id}", name="delete")
     */
    public function delete($id) : Response
    {
        $dat = $this->getDoctrine()->getRepository(Achat::class);
        $achat = $dat->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($achat);
        $manager->flush();
        return $this->redirectToRoute("achat");
    }

    /**
     * @Route("/achat/delete_one_cart/{id}", name="delete_one_cart")
     */
    public function delete_one_cart($id) : Response
    {
        $dat = $this->getDoctrine()->getRepository(Achat::class);
        $achat = $dat->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($achat);
        $manager->flush();
        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/achat/update/{id}", name="update")
     */
    public function update($id,Request $request) : Response
    {
        $dat = $this->getDoctrine()->getRepository(Achat::class);
        $ach =$dat->find($id);

        $form = $this->createForm(AchatType::class,$ach);
        $form->add("Update",SubmitType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($ach);
            $manager->flush();
            return $this->redirectToRoute("achat");
        }
        return $this->render('achat/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @param Request $request
     * @Route("/achat/add", name="add")
     */
    public function add(Request $request) : Response
    {
        $ach = new Achat();
        $form = $this->createForm(AchatType::class,$ach);
        $form->add("Ajouter",SubmitType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($ach);
            $manager->flush();
            return $this->redirectToRoute("achat");
        }
        return $this->render('achat/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
