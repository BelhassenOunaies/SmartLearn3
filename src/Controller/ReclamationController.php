<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="reclamation")
     */
    public function index(): Response
    {
        $dat = $this->getDoctrine()->getRepository(Reclamation::class);
        $reclamations = $dat->findAll();
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    /**
     * @Route("/reclamation/inspecter/{id}", name="inspecter_reclamation")
     */
    public function inspecter($id): Response
    {
        $dat = $this->getDoctrine()->getRepository(Reclamation::class);
        $reclamation = $dat->find($id);
        return $this->render('reclamation/inspecter.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/reclamation/make_resolved/{id}", name="make_resolved")
     */
    public function make_resolved($id,Request $request): Response
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {


        $reclamation = new Reclamation();
        $dat = $this->getDoctrine()->getRepository(Reclamation::class);
        $reclamation = $dat->find($id);
        $reclamation->setResolu(1);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($reclamation);
        $manager->flush();

                $jsonData = array();

                return new JsonResponse($jsonData);
        }

        else
        {
            return $this->redirectToRoute("reclamation");
        }
    }


    /**
     * @Route("/reclamation/reply/{id}", name="reply")
     */
    public function reply($id,Request $request,MailerInterface $mailer): Response
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $reclamation = new Reclamation();
            $dat = $this->getDoctrine()->getRepository(Reclamation::class);
            $reclamation = $dat->find($id);
            $reclamation->setReply($request->get("reply"));
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($reclamation);
            $manager->flush();

            $jsonData = array();

            //////////////////////////
            $email = (new Email())
                ->from('ahmed.touil1@esprit.tn')
                ->to('athomield@hotmail.com')
                ->subject($reclamation->getTitle())
                ->text($reclamation->getReply());

            /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
            $sentEmail = $mailer->send($email);

            return new JsonResponse($jsonData);
        }

        else
        {
            return $this->redirectToRoute("reclamation");
        }
    }

    /**
     * @Route("/reclamation/delete/{id}", name="delete_reclamation")
     */
    public function delete($id) : Response
    {
        $dat = $this->getDoctrine()->getRepository(Reclamation::class);
        $reclamation = $dat->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($reclamation);
        $manager->flush();
        return $this->redirectToRoute("reclamation");
    }

    /**
     * @Route("/reclamation/update/{id}", name="update_reclamation")
     */
    public function update($id,Request $request) : Response
    {
        $dat = $this->getDoctrine()->getRepository(Reclamation::class);
        $ach =$dat->find($id);

        $form = $this->createForm(ReclamationType::class,$ach);
        $form->add("Update",SubmitType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($ach);
            $manager->flush();
            return $this->redirectToRoute("reclamation");
        }
        return $this->render('reclamation/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @param Request $request
     * @Route("/reclamation/add", name="add_reclamation")
     */
    public function add(Request $request,MailerInterface $mailer) : Response
    {
        $ach = new Reclamation();
        $form = $this->createForm(ReclamationType::class,$ach);
        $form->add("Ajouter",SubmitType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $date = new \DateTime('@'.strtotime('now'));
            $ach->setResolu(0);
            $ach->setReclamationDate($date);
            $ach->setHId(0);
            $ach->setPoiId(0);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($ach);
            $manager->flush();
        //////////////////////////
            $email = (new Email())
                ->from('ahmed.touil1@esprit.tn')
                ->to('athomield@hotmail.com')
                ->subject($ach->getTitle())
                ->text($ach->getDescription());

            /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
            $sentEmail = $mailer->send($email);

            return $this->redirectToRoute("dashboard");
        }
        return $this->render('reclamation/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('ahmed.touil1@esprit.tn')
            ->to('athomield@hotmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!');

        /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
        $sentEmail = $mailer->send($email);


        // $messageId = $sentEmail->getMessageId();

        // ...
    }

}
