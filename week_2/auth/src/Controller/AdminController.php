<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    private UserRepository $users;
    private Security $security;

    public function __construct(UserRepository $users, Security $security)
    {
        $this->users = $users;
        $this->security = $security;
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_index');
        }

        $list = $this->users->findBy([], ['createdAt' => 'DESC'], 100);

        return $this->render('admin/index.html.twig', [
            'usersList' => $list
        ]);
    }
}
