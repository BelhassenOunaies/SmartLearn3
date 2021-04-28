<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\Course1Type;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/courses/front")
 */
class CoursesFrontController extends AbstractController
{
    /**
     * @Route("/", name="courses_front_index", methods={"GET"})
     */
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('courses_front/index.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="courses_front_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $course = new Course();
        $form = $this->createForm(Course1Type::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();

            return $this->redirectToRoute('courses_front_index');
        }

        return $this->render('courses_front/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="courses_front_show", methods={"GET"})
     */
    public function show(Course $course): Response
    {
        return $this->render('courses_front/show.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="courses_front_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Course $course): Response
    {
        $form = $this->createForm(Course1Type::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('courses_front_index');
        }

        return $this->render('courses_front/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="courses_front_delete", methods={"POST"})
     */
    public function delete(Request $request, Course $course): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($course);
            $entityManager->flush();
        }

        return $this->redirectToRoute('courses_front_index');
    }
    /**
     * @param CourseRepository $repository
     * @param Request $request
     * @return Response
     * @Route("/course/recherchefront",name="rechercheCoursefront")
     */
    public function Recherchefront(CourseRepository $repository,Request $request)
    {

        $data=$request->get('search');
        $em=$repository->findcoursebyId($data);

        return $this->render('courses_front/index.html.twig',[
            'courses'=>$em,

        ]);
    }
}
