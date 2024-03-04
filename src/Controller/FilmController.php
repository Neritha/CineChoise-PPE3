<?php

namespace App\Controller;

use App\Entity\Movie;
use Twig\Environment;
use App\Repository\MovieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FilmController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


    #[Route('/films', name: 'films', methods:['GET'])]
    public function listeFilms(MovieRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {

        $films = $paginator->paginate(
            $repo->listeFilmsComplete(),
            $request->query->getInt('page', 1), 
            9
        );

        return $this->render('film/listeFilms.html.twig', [
            'lesFilms' => $films
        ]);
    }

    #[Route('/films/{id}', name: 'film_fiche', methods:['GET'])]
    public function ficheMovi(int $id, MovieRepository $repo): Response
    {
        $film = $repo->find($id);
        return new Response($this->twig->render('film/ficheFilm.html.twig', [
            'leFilm' => $film,
        ]));
    }

}
