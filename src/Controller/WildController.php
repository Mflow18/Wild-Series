<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Episode;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\ProgramSearchType;

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

        $form = $this->createForm(
            ProgramSearchType::class,
            null,
            ['method' => Request::METHOD_GET]
        );

        return $this->render('wild/index.html.twig',
            ['programs' => $programs,
                'form' => $form->createView(),
            ]
        );

    }

    /**
     * @Route("/{Slug}", name="slug", methods={"GET"})
     */
    public function show(Program $program): Response
    {
        return $this->render('/Wild/show.html.twig', [
            'program' => $program,
        ]);
    }


    /**
     * @Route("/category/{categoryName}", requirements={"categoryName"="[a-z0-9-]+"}, defaults={"category"="Aucune série sélectionnée, veuillez choisir une série"}, name="show_category")
     */
    public function showByCategory(string $categoryName)
    {
        $categoryName = str_replace("-", " ", $categoryName);

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(["name" => mb_strtolower($categoryName)]);

        if (!$categoryName) {
            throw $this
                ->createNotFoundException("Please give us a valid category");
        } elseif (!$category) {
            throw $this
                ->createNotFoundException("No category with " . $categoryName . " name, found in category's table.");
        }

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(["category" => $category->getId()],
                ['id' => 'desc'], 3, 0);

        return $this->render('wild/category.html.twig', [
            'programs' => $programs,
            'category' => $category,
        ]);
    }


    /**
     * @Route("/program/{programName}", requirements={"programName"="[a-z0-9-]+"}, defaults={"program"="Aucune série sélectionnée, veuillez choisir une série"}, name="show_programs")
     */
    public function showByProgram(string $programName)
    {
        $programName = str_replace("-", " ", $programName);
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(["title" => mb_strtolower($programName)]);

        if (!$programName) {
            throw $this
                ->createNotFoundException("No program with " . $programName . " name, found in program's table.");
        }

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(["program" => $programs]);

        return $this->render('wild/program.html.twig', [
            'programs' => $programs,
            'seasons' => $seasons,
        ]);
    }

    /**
     * @Route("/season/{id}", requirements={"id"="[a-z0-9-]+"}, defaults={"id"="Aucune série sélectionnée, veuillez choisir une série"}, name="show_seasons")
     */
    public function showBySeason(int $id)
    {
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->find($id);

        if (!$season) {
            throw $this
                ->createNotFoundException("Please give us a valid season ID");
        }
        return $this->render('wild/episodes.html.twig', [
            'episodes' => $season->getEpisodes(),
            'program' => $season->getProgram(),
            'seasons' => $season,
        ]);
    }

    /**
     * @route("/episode/{id}", defaults={"id"="Aucun épisode sélectionnée, veuillez choisir une série"}, name="show_episode")
     */
    public function showEpisode(Episode $episode)
    {
        return $this->render('wild/episode.html.twig', [
                'episode' => $episode
            ]
        );
    }



}