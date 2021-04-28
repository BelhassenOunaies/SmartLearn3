<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/course")
 */
class CourseController extends AbstractController
{
    /**
     * @Route("/", name="course_index", methods={"GET"})
     */
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('course/index.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);
    }
    /**
     * @Route("/pdf", name="course_pdf", methods={"GET","POST"})
     */
    public function pdf(CourseRepository $courseRepository): Response
    {


        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->render('course/mypdf.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("pdf.pdf", [
            "Attachment" => true
        ]);
    }


    /**
     * @Route("/new", name="course_new", methods={"GET","POST"})
     */
    public function new(Request $request,\Swift_Mailer $mailer): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();
            $mail = (new \Swift_Message('Thanks for signing up!'))
                // On attribue l'expéditeur
                ->setFrom('smartlearnpi@gmail.com')
                // On attribue le destinataire
                ->setTo("belhassen.ounaies@esprit.tn")
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'course/mail.html.twig'
                    ),
                    'text/html'
                )
            ;
            $mailer->send($mail);

            return $this->redirectToRoute('course_index');
        }

        return $this->render('course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="course_show", methods={"GET"})
     */
    public function show(Course $course): Response
    {
        return $this->render('course/show.html.twig', [
            'course' => $course,
        ]);

    }

    /**
     * @Route("/{id}/edit", name="course_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Course $course): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('course_index');
        }

        return $this->render('course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="course_delete", methods={"POST"})
     */
    public function delete(Request $request, Course $course): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($course);
            $entityManager->flush();
        }

        return $this->redirectToRoute('course_index');
    }
    /**
     * @param CourseRepository $repository
     * @param Request $request
     * @return Response
     * @Route("/course/recherche",name="rechercheCourse")
     */
    public function Recherche(CourseRepository $repository,Request $request)
    {

        $data=$request->get('search');
        $em=$repository->findcoursebyId($data);

        return $this->render('course/index.html.twig',[
            'courses'=>$em,

        ]);
    }



    /*public function search(Request $request,NormalizerInterface $Normalizer)
    {
        $courserepository = $this->getDoctrine()->getRepository(Course::class);
        $requestString=$request->get('searchValue');
        $course = $courserepository->findStudentById($requestString);
        $jsonContent = $Normalizer->normalize($course, 'json',['groups'=>'course']);
        $retour=json_encode($jsonContent);
        return new Response($retour);

    }*/


}
