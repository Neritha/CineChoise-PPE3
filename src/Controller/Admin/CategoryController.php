<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /*
    #[Route('/admin/category', name: 'admin_category')]
    public function index(): Response
    {
        return $this->render('admin/category/listeCategory.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
    */
    #[Route('/admin/category', name: 'admin_category', methods:['GET'])]
    public function listeCategory(CategoryRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $category = $paginator->paginate(
            $repo->listeCategoryCompleteAdmin(),
            $request->query->getInt('page', 1), 
            9
        );

        return $this->render('admin/category/listeCategory.html.twig', [
            'lesCategory' => $category
        ]);
    }

    

    #[Route('/admin/category/supr/{id}', name: 'admin_category_supr', methods: ['GET'])]
    public function suprCategory(Category $category, EntityManagerInterface $manager): Response
    {
        $nbFilm = $category->getMovies()->count();

        /*if ($nbFilm > 0) {
            $this->addFlash("danger", "Vous ne pouvez pas supprimer cette category car $nbFilm film(s) y sont associés !");
        } else {
            $manager->remove($category);
            $manager->flush();

            $this->addFlash("success", "La category a bien été supprimé!");
        }*/

        $manager->remove($category);
        $manager->flush();

        $this->addFlash("success", "La category a bien été supprimé!");

        return $this->redirectToRoute('admin_category');
    }
    


    #[Route('/admin/category/ajout', name: 'admin_category_ajout', methods: ['GET', 'POST'])]
    #[Route('/admin/category/modif/{id}', name: 'admin_category_modif', methods: ['GET', 'POST'])]
    public function ajoutModifCategory(Category $category = null, Request $request, EntityManagerInterface $manager): Response
    {
        if (!$category) {
            $category = new Category(); // Initialisez une nouvelle instance de Film si $film est null
            $mode = "ajouté";
        } else {
            $mode = "modifié";
        }
    
        $form = $this->createForm(CategoryType::class, $category); // provient du formulaire
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($category);
            $manager->flush();
    
            $this->addFlash("success", "La categorie a bien été $mode!");
    
            return $this->redirectToRoute('admin_category');
        }
    
        return $this->render('admin/category/formAjoutModifCategory.html.twig', [
            'formCategory' => $form->createView(),
        ]);
    }


}
