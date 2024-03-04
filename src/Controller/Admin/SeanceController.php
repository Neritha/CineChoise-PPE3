<?php

namespace App\Controller\Admin;

use App\Entity\Session;
use App\Form\SeanceType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SeanceController extends AbstractController
{
    #[Route('/admin/seances', name: 'admin_seances', methods:['GET'])]
    public function listeSeances(SessionRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $seances = $paginator->paginate(
            $repo->listeSeancesCompleteAdmin(),
            $request->query->getInt('page', 1), 
            9
        );

        return $this->render('admin/seance/listeSeances.html.twig', [
            'lesSeances' => $seances
        ]);
    }

    #[Route('/admin/sceance/supr/{id}', name: 'admin_sceance_supr', methods: ['GET'])]
    public function suprSceance(Session $seance, EntityManagerInterface $manager): Response
    {
        
        $manager->remove($seance);
        $manager->flush();

        $this->addFlash("success", "La seance a bien été supprimé!");
     

        return $this->redirectToRoute('admin_seances');
    }


        #[Route('/admin/seance/ajout', name: 'admin_seance_ajout', methods: ['GET', 'POST'])]
        #[Route('/admin/seance/modif/{id}', name: 'admin_seance_modif', methods: ['GET', 'POST'])]
        public function ajoutModifSceance(Session $seance = null, Request $request, EntityManagerInterface $manager): Response
        {
            if (!$seance) {
                $seance = new Session(); // Initialisez une nouvelle instance de Film si $film est null
                $mode = "ajouté";
            } else {
                $mode = "modifié";
            }
        
            $form = $this->createForm(SeanceType::class, $seance); // provient du formulaire
        
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {

                $manager->persist($seance);
                $manager->flush();
        
                $this->addFlash("success", "La seance a bien été $mode!");
        
                return $this->redirectToRoute('admin_seances');
            }
        
            return $this->render('admin/seance/formAjoutModifSeance.html.twig', [
                'formSeance' => $form->createView(),
            ]);
        }

}
