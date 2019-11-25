<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Category;
use App\Entity\Program;

/**
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                "No program found in program's table."
            );
        }

        return $this->render('wild/index.html.twig',
            ['programs' => $programs]
        );

    }

    /**
     * @Route("/show/{slug}", requirements={"slug"="[a-z0-9-]+"}, defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"}, name="slug")
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException("No slug has been sent to find a program in program's table.");
        }

        $slug = str_replace("-", " ", $slug);
        $slug = ucwords($slug);

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$program) {
            throw $this->createNotFoundException(
                "No program with " . $slug . " title, found in program's table."
            );
        }
        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }


    /**
     * @Route("/show/{categoryName}", requirements={"slug"="[a-z0-9-]+"}, defaults={"category"="Aucune série sélectionnée, veuillez choisir une série"}, name="show_category")
     */
    public function showByCategory(string $categoryName)
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException("Please give us a valid category");
        }

        $categoryName = str_replace("-", " ", $categoryName);
        $categoryName = ucwords($categoryName);

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(["name" => mb_strtolower($categoryName)]);

        if (!$category) {
            throw $this
                ->createNotFoundException("No category with " . $categoryName . " name, found in category's table.");
        }

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(["category" => $category->getId()],
        ['id' =>'desc'], 3, 0);

        return $this->render('wild/category.html.twig', [
            'programs' => $programs,
            'category' => $category,
        ]);
    }
}