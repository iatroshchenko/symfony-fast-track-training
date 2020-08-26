<?php

namespace App\Controller;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class PagesController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Environment $twig, ConferenceRepository $repository)
    {
        return new Response($twig->render('pages/index.html.twig', [
            'conferences' => $repository->findAll()
        ]));
    }
}
