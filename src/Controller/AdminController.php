<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminCreateFormType;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    private $adminRepository;
    private $entityManager;
    private $passwordEncoder;

    public function __construct(AdminRepository $adminRepository, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->adminRepository = $adminRepository;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/make-admin", name="admin")
     */
    public function index(Request $request)
    {
        $adminsCount = $this->adminRepository->count([]);
        if ($adminsCount > 0) {
            return new Response("disabled. Already have an admin");
        }

        $form = $this
            ->createForm(AdminCreateFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->getData()['username'];
            $password = $form->getData()['password'];

            $admin = new Admin();
            $admin->setUsername($username);
            $password = $this->passwordEncoder->encodePassword($admin, $password);
            $admin->setPassword($password);
            $admin->setRoles([Admin::ROLE_ADMIN]);

            $this->entityManager->persist($admin);
            $this->entityManager->flush();
        }

        $form = $form->createView();
        return $this->render('admin/index.html.twig', compact('form'));
    }
}
