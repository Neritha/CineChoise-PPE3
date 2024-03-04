<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /*#[Route('/admin/user', name: 'app_admin_user')]
    public function index(): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }*/

    #[Route('/admin/user', name: 'admin_user', methods:['GET'])]
    public function listeUser(UserRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $paginator->paginate(
            $repo->listeUserCompleteAdmin(),
            $request->query->getInt('page', 1), 
            9
        );

        return $this->render('admin/user/listeUser.html.twig', [
            'lesUsers' => $users
        ]);
    }

}
