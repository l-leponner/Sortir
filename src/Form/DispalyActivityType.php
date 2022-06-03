<?php

namespace App\Form;

use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DispalyActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Nom de la sortie:'
            ])
            ->add('dateTimeBeginning', DateTimeType::class,[
                'label' =>'Date et heure de la sortie:'
            ])
            ->add('duration')
            ->add('dateLimitRegistration')
            ->add('maxNbRegistrations')
            ->add('infosActivity')
            ->add('participants')
            ->add('organizer')
            ->add('campus')
            ->add('place')
            ->add('state')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
