<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\Movie;
use App\Entity\Session;
use Doctrine\ORM\Mapping\OrderBy;
use App\Repository\MovieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('date', DateType::class,[
                'input'=>'string',
                'label' => 'date de la séance'
            ])

            ->add('heure', TimeType::class,[
                'input_format' => 'H:i',
                'input'=>'string',
                'label' => 'heure de la séance',
                'minutes' => range(0, 55, 5),
                'hours' => range(7, 23),
            ])

            ->add('film', EntityType::class, [
                'class'=>Movie::class,
                'choice_label'=>'nom',
                'query_builder' => function (MovieRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nom', 'ASC'); // Tri par le champ 'nom' en ordre alphabétique
                },
                'label' => "Film associée",
                'attr'=>[
                    'class'=>"selectStyles",
                ]
            ])

            ->add('salle', EntityType::class, [
                'class'=>Room::class,
                'choice_label'=>'nom',
                'label' => "Salle associée",
                'attr'=>[
                    'class'=>"selectStyles",
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}

