<?php

namespace App\Controller;

use Twig\Environment;
use App\Repository\RoomRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SalleController extends AbstractController
{
    private $twig;

        public function __construct(Environment $twig)
        {
            $this->twig = $twig;
        }

    #[Route('/salles', name: 'salles', methods:['GET'])]

    public function listeSalles (RoomRepository $repo, PaginatorInterface $paginator, Request $request) : Response
    {
        $salles = $paginator->paginate(
            $repo->listeSallesComplete(),
            $request->query->getInt('page', 1), 
            9
        );

        return $this->render('salle/listeSalles.html.twig', [
            'lesSalles' => $salles
        ]);
    }

    #[Route('/salles/{id}', name: 'salle_fiche', methods:['GET'])]
    public function ficheSalle(int $id, RoomRepository $repo): Response
    {
        $salle = $repo->find($id);
        return new Response($this->twig->render('salle/ficheSalle.html.twig', [
            'laSalle' => $salle,
        ]));
    }
}
