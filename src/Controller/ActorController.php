<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ActorController extends AbstractController
{
    /**
     * @Route("/actor/{id}", name="actor")
     * @param Actor $actor
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Actor $actor) : Response
    {
        return $this->render('actor/index.html.twig', [
            'controller_name' => 'ActorController',
            'actor' => $actor
        ]);
    }
}