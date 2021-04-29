<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Coupon;
use App\Form\AchatType;
use App\Form\CouponType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CouponController extends AbstractController
{
    /**
     * @Route("/coupon", name="coupon")
     */
    public function index(): Response
    {
        $dat = $this->getDoctrine()->getRepository(Coupon::class);
        $achats = $dat->findAll();
        return $this->render('coupon/index.html.twig', [
            'coupons' => $achats,
        ]);
    }

    /**
     * @Route("/coupon/delete/{id}", name="delete_coupon")
     */
    public function delete($id) : Response
    {
        $dat = $this->getDoctrine()->getRepository(Coupon::class);
        $achat = $dat->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($achat);
        $manager->flush();
        return $this->redirectToRoute("coupon");
    }

    /**
     * @param Request $request
     * @Route("/coupon/add", name="add_coupon")
     */
    public function add(Request $request) : Response
    {
        $ach = new Coupon();
        $form = $this->createForm(CouponType::class,$ach);
        $form->add("Ajouter",SubmitType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $ach->setUsed(0);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($ach);
            $manager->flush();
            return $this->redirectToRoute("coupon");
        }
        return $this->render('coupon/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
