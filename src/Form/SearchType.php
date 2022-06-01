<?php

namespace App\Form;

use App\Entity\Activity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameKeyword', \Symfony\Component\Form\Extension\Core\Type\SearchType::class, [
                'label' => 'Le nom de la sortie contient : ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search'
                ]

            ])
            ->add('minDateTimeBeginning', DateTimeType::class, [
                'label' => 'Entre',
                'required' => false
            ])
            ->add('maxDateTimeBeginning', DateTimeType::class, [
                'label' => 'et',
                'required' => false
            ])

            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'choice_label' => 'name',
                'class' => 'App\Entity\Campus',
                'placeholder' => '-- Select a campus --',
                'required' => false
            ])
            ->add('filterActiOrganized', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false
            ])
            ->add('filterActiJoined', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false
            ])
            ->add('filterActiNotJoined', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false
            ])
            ->add('filterActiEnded', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            //unmapping du formulaire de la classe Activity
//            'data_class' => Activity::class,
        ]);
    }
}
