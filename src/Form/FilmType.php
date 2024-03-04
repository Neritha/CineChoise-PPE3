<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Session;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Titre du film",
                'required' => false,
                'attr' => [
                    "placeholder" => "Saisir le titre du film"
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description du film",
                'attr' => [
                    'required' => false,
                    "placeholder" => "Saisir la description du film"
                ]
            ])
            ->add('affiche', HiddenType::class)
            ->add('afficheFile', FileType::class, [
                'mapped'=>false,
                'required'=>false,
                'label'=>"Charger l'affiche du film",
                'attr'=>[
                    'accept'=>".jpg,.png"
                ],
                'row_attr'=>[
                    'class'=>"d-none"
                ]
            ])
/*
            ->add('date', IntegerType::class, [
                'label'=>"Date de sortie",
                'required'=>false,
                'attr'=>[
                    "placeholder"=>"Saisir la date de sortie du film (ex: 2012)"
                ]
            ])*/

            ->add('date', DateType::class,[
                'input'=>'string',
                'label' => 'date de sortie'
            ])


           /* ->add('date', DateType::class,[
                //'input' => 'string',
                'label' => 'date de sortie du film',
            ])*/
            
            /*->add('duree', TextType::class, [
                'label'=>"Durée du film",
                'required'=>false,
                'attr'=>[
                    
                    "placeholder"=>"Saisir la durée du film (ex: 2:04)"
                ]
            ])*/

            ->add('duree', TimeType::class,[
                'input_format' => 'H:i',
                'input'=>'string',
                'label' => 'Durée du film',
                'hours' => range(0, 10),
            ])

            /*->add('duree', TimeType::class,[
                'label'=>"Durée du film",
                //'input_format' => 'H:i',
                'input'=>'string',
                //'placeholder' => 'Select a value',
                'placeholder' => [
                    'hour' => 'Hour', 'minute' => 'Minute',
                ],
            ])*/


            ->add('categories', EntityType::class, [
                'class'=>Category::class,
                'choice_label'=>'nom',
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nom', 'ASC'); // Tri par le champ 'nom' en ordre alphabétique
                },
                'label' => "Catégorie(s) associées",
                'multiple' => true,
                'by_reference'=>false,
                'required'=>false,
                'attr'=>[ 
                    'class'=>"selectStyles",
                ]
            ])

            /*->add('sessions', EntityType::class, [
                'class'=>Session::class,
                'choice_label'=>'id',
                'label' => "Séance(s) associées",
                'required'=>false,
                'multiple' => true,
                'by_reference'=>false,
                'attr'=>[
                    'class'=>"selectStyles",
                ]
            ])*/ // la séance associer au film choisi lors de la création de la séance, pas lors de la création du film


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
            
        ]);
    }
}
