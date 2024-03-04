<?php

namespace App\Form;

use App\Entity\Category;
use App\Model\FiltreFilmAdmin;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FiltreMovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr'=>[
                    'placeholder'=>"Recherche sur le titre"
                ],
                'required'=>false,
                'label'=>false
            ])
            ->add('category', EntityType::class, [
                'class'=>Category::class,
                'query_builder'=>function(CategoryRepository $repo){
                        return $repo->listeCategorySimple();
                },
                'attr'=>[
                    'placeholder'=>"Recherche par catégorie"
                ],
                'choice_label'=>'nom',
                'label'=>false,
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'method'=>'GET',
            'csrf_protection'=>false,
            'data_class'=>FiltreFilmAdmin::class
        ]);
    }
}
