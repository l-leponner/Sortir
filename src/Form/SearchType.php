<?php

namespace App\Form;

use App\Entity\Activity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('name')
//            ->add('dateTimeBeginning')
//            ->add('duration')
//            ->add('dateLimitRegistration')
//            ->add('maxNbRegistrations')
//            ->add('infosActivity')
//            ->add('participants')
//            ->add('organizer')
//            ->add('place')
//            ->add('state')
            ->add('campus', EntityType::class, [
                'label' => 'Campus',
                'choice_label' => 'name',
                'class' => 'App\Entity\Campus',
                'placeholder' => '-- Select a campus --'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
