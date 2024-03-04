<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Form\SalleType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SalleController extends AbstractController
{
    /*#[Route('/admin/salles', name: 'admin_salles', methods:['GET'])]
    public function listeSalles(RoomRepository $repo): Response
    {
        $salles = $repo->findAll();
        return $this->render('admin/salle/listeSalles.html.twig', ['lesSalles' => $salles,
        ]);
    }
    /*public function index(): Response
    {
        return $this->render('admin/salle/index.html.twig', [
            'controller_name' => 'SalleController',
        ]);
    }*/

    #[Route('/admin/salles', name: 'admin_salles', methods:['GET'])]
    public function listeSalles(RoomRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $salles = $paginator->paginate(
            $repo->listeSallesCompleteAdmin(),
            $request->query->getInt('page', 1), 
            9
        );

        return $this->render('admin/salle/listeSalles.html.twig', [
            'lesSalles' => $salles
        ]);
    }

    #[Route('/admin/salle/supr/{id}', name: 'admin_salle_supr', methods: ['GET'])]
    public function suprSalle(Room $salle, EntityManagerInterface $manager): Response
    {
        $nbSeance = $salle->getSessions()->count();

        if ($nbSeance > 0) {
            $this->addFlash("danger", "Vous ne pouvez pas supprimer cette salle car $nbSeance Séance(s) y sont associés!");
        } else {
            $manager->remove($salle);
            $manager->flush();

            $this->addFlash("success", "La salle a bien été supprimé!");
        }

        return $this->redirectToRoute('admin_salles');
    }


        #[Route('/admin/salle/ajout', name: 'admin_salle_ajout', methods: ['GET', 'POST'])]
        #[Route('/admin/salle/modif/{id}', name: 'admin_salle_modif', methods: ['GET', 'POST'])]
        public function ajoutModifSalle(Room $salle = null, Request $request, EntityManagerInterface $manager): Response
        {
            if (!$salle) {
                $salle = new Room(); // Initialisez une nouvelle instance de Film si $film est null
                $mode = "ajouté";
            } else {
                $mode = "modifié";
            }
        
            $form = $this->createForm(SalleType::class, $salle); // provient du formulaire
        
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {

                $manager->persist($salle);
                $manager->flush();
        
                $this->addFlash("success", "La salle a bien été $mode!");
        
                return $this->redirectToRoute('admin_salles');
            }
        
            return $this->render('admin/salle/formAjoutModifSalle.html.twig', [
                'formSalle' => $form->createView(),
            ]);
        }

}
