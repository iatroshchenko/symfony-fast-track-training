<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use http\Env;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Entity\Conference;
use Symfony\Component\HttpFoundation\Request;

class ConferenceController extends AbstractController
{
    protected $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/conference", name="conference-list")
     */
    public function index(ConferenceRepository $repository)
    {
        $conferences = $repository->findAll();
        $params = compact('conferences');
        return new Response($this->twig->render('conference/list.html.twig', $params));
    }

    /**
     * @Route("/conference/{id}", name="conference-show")
     */
    public function show(Request $request, Conference $conference, CommentRepository $commentRepository) {
        $offset = max(0, $request->query->getInt('offset', 0));
        $comments = $commentRepository->getCommentPaginator($conference, $offset);
        $previous = $offset - CommentRepository::PAGINATOR_PER_PAGE;
        $next = min(count($comments), $offset + CommentRepository::PAGINATOR_PER_PAGE);
        $params = compact('conference', 'comments', 'offset', 'previous', 'next');

        return new Response($this->twig->render('conference/show.html.twig', $params));
    }
}
