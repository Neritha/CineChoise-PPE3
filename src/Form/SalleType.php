<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class SalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=> 'Nom de la salle',
                'attr' => [
                    "placeholder" => "saisir le nom de la salle"
                ]
            ])

            ->add('capaciter', IntegerType::class, [
                'label'=>"Capacité de la salle",
                'attr'=>[
                    "placeholder"=>"Saisir la capacité de la salle"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
