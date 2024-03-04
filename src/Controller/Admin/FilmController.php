<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use App\Form\FilmType;
use App\Form\FiltreMovieType;
use App\Model\FiltreFilmAdmin;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FilmController extends AbstractController
{
        #[Route('/admin/films', name: 'admin_films', methods:['GET'])]
        public function listeFilms(MovieRepository $repo, PaginatorInterface $paginator, Request $request): Response
        {
            $filtre = new FiltreFilmAdmin();
            $formFiltreMovieAdmin=$this->createForm(FiltreMovieType::class, $filtre);
            $formFiltreMovieAdmin->handleRequest($request);

            $films = $paginator->paginate(
                $repo->listeFilmsCompleteAdmin($filtre),
                $request->query->getInt('page', 1), 
                9
            );
    
            return $this->render('admin/film/listeFilms.html.twig', [
                'lesFilms' => $films,
                'formFiltreMovieAdmin'=>$formFiltreMovieAdmin->createView()
            ]);
        }

        #[Route('/admin/film/ajout', name: 'admin_film_ajout', methods: ['GET', 'POST'])]
        #[Route('/admin/film/modif/{id}', name: 'admin_film_modif', methods: ['GET', 'POST'])]
        public function ajoutModifFilm(Movie $film = null, Request $request, EntityManagerInterface $manager): Response
        {
            if (!$film) {
                $film = new Movie(); // Initialisez une nouvelle instance de Film si $film est null
                $mode = "ajouté";
            } else {
                $mode = "modifié";
            }
        
            $form = $this->createForm(FilmType::class, $film);
        
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                //on récupère l'image sélectionnée
                $fichierAffiche = $form->get('afficheFile')->getData();
                
                if ($fichierAffiche != null)
                {
                    //on supprime l'ancien fichier
                    if ($film->getAffiche() != "default.jpg")
                    {
                        \unlink($this->getParameter('affichesFilmsDestination') . $film->getAffiche());
                    }
                    
                    //on crée le nom du nouveau fichier
                    $fichier = md5(\uniqid()) . "." . $fichierAffiche->guessExtension();

                    //on déplace le fichier chargé dans le dossier public
                    $fichierAffiche->move($this->getParameter('affichesFilmsDestination'), $fichier);
                    $film->setAffiche($fichier);
                }

                $manager->persist($film);
                $manager->flush();
        
                $this->addFlash("success", "Le film a bien été $mode!");
        
                return $this->redirectToRoute('admin_films');
            }
        
            return $this->render('admin/film/formAjoutModifFilm.html.twig', [
                'formFilm' => $form->createView(),
            ]);
        }

        #[Route('/admin/film/supr/{id}', name: 'admin_film_supr', methods: ['GET'])]
        public function suprFilm(Movie $film, EntityManagerInterface $manager): Response
        {
            $nbSession = $film->getSessions()->count();

            if ($nbSession > 0) {
                $this->addFlash("danger", "Vous ne pouvez pas supprimer ce film car $nbSession Séance(s) y sont associés!");
            } else {
                $manager->remove($film);
                $manager->flush();
    
                $this->addFlash("success", "Le film a bien été supprimé!");
            }
    
            return $this->redirectToRoute('admin_films');
        }
}
