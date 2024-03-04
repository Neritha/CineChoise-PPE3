<?php

namespace App\Controller;

use Twig\Environment;
use App\Entity\Session;
use App\Repository\SessionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\twig;

class SeanceController extends AbstractController
{
    
    /*public function listeFilms(SessionRepository $repo): Response
    {
        $seances=$repo->findAll();

        return $this->render('seance/listeSeances.html.twig', [
            'lesSeances' => $seances,
        ]);
    }*/
    private $twig;

        public function __construct(Environment $twig)
        {
            $this->twig = $twig;
        }

    #[Route('/seances', name: 'seances', methods:['GET'])]
    public function listeSeances (SessionRepository $repo, PaginatorInterface $paginator, Request $request) : Response
    {
        $seances = $paginator->paginate(
            $repo->listeSeancesComplete(),
            $request->query->getInt('page', 1), 
            9
        );

        return $this->render('seance/listeSeances.html.twig', [
            'lesSeances' => $seances
        ]);
    }

    #[Route('/seances/{id}', name: 'seance_fiche', methods:['GET'])]
    public function ficheSeance(int $id, SessionRepository $repo): Response
    {
        $seance = $repo->find($id);
        return new Response($this->twig->render('seance/ficheSeance.html.twig', [
            'laSeance' => $seance,
        ]));
    }
}
