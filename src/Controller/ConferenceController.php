<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Entity\Conference;
use Symfony\Component\HttpFoundation\Request;
use App\SpamChecker;

class ConferenceController extends AbstractController
{
    protected $twig;
    protected $entityManager;
    protected $spamChecker;

    public function __construct(Environment $twig, EntityManagerInterface $entityManager, SpamChecker $spamChecker)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
        $this->spamChecker = $spamChecker;
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

    public function checkForSpam(Comment $comment, Request $request): int
    {
        $context = [
            'user_ip' => $request->getClientIp(),
            'user_agent' => $request->headers->get('user-agent'),
            'referrer' => $request->headers->get('referrer'),
            'permalink' => $request->getUri()
        ];
        return $this->spamChecker->getSpamScore($comment, $context);
    }

    /**
     * @Route("/conference/{slug}", name="conference-show")
     */
    public function show(
        Request $request,
        Conference $conference,
        CommentRepository $commentRepository,
        string $photoDir
    ) {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setConference($conference);
            $comment->setCreatedAt(new \DateTime());

            if ($photo = $form['photo']->getData()) {
                $filename = bin2hex(random_bytes(6) . '.' . $photo->guessExtension());
                try {
                    $photo->move($photoDir, $filename);
                } catch (FileException $exception) {
                    dd('unable to upload photo');
                    // unable to upload the photo, give up
                }
                $comment->setPhotoFilename($filename);
            }

            $this->entityManager->persist($comment);

            if ($this->checkForSpam($comment, $request) > 0) {
                throw new \RuntimeException('Blatant spam, go away!');
            };

            $this->entityManager->flush();

            return $this->redirectToRoute('conference-show', ['slug' => $conference->getSlug()]);
        }

        $offset = max(0, $request->query->getInt('offset', 0));
        $comments = $commentRepository->getCommentPaginator($conference, $offset);
        $previous = $offset - CommentRepository::PAGINATOR_PER_PAGE;
        $next = min(count($comments), $offset + CommentRepository::PAGINATOR_PER_PAGE);

        $commentForm = $form->createView();

        $params = compact('conference', 'comments', 'offset', 'previous', 'next', 'commentForm');

        return new Response($this->twig->render('conference/show.html.twig', $params));
    }
}
