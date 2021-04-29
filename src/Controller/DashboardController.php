<?php

namespace App\Controller;

use App\Entity\Achat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("dashboard/ajouter_cours/{id}")
     */
    public function ajouter_cours($id,Request $request) {
        $date = new \DateTime('@'.strtotime('now'));

        $ach = new Achat();
        $ach->setAchatFinalise(0);
        $ach->setCourseId($id);
        $ach->setUserId(0);
        $ach->setDateAchat($date);

        $manager = $this->getDoctrine()->getManager();/*
            ->getRepository('AppBundle:Achat')
            ->get();*/
        $manager->persist($ach);
        $manager->flush();

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $jsonData = array();
            $temp = array(
                'name' => $ach->getAchatFinalise(),
                'coursId' => $ach->getCourseId(),
                'userId' => $ach->getUserId(),
                'dateAchat' => $ach->getDateAchat()
            );
            $jsonData[0] = $temp;
            /*$jsonData = array();
            $idx = 0;
            foreach($students as $student) {
                $temp = array(
                    'name' => $student->getName(),
                    'address' => $student->getAddress(),
                );
                $jsonData[$idx++] = $temp;
            }*/
            return new JsonResponse($jsonData);
        } else {
            return $this->render('student/ajax.html.twig');
        }
    }
}
