<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Campus;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie',
            ])
            ->add('dateTimeBeginning', DateTimeType::class, [
                'label' => 'Date et heure de la sortie : ',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée : '
            ])
            ->add('dateLimitRegistration', DateType::class, [
                'label' => 'Date limite de la sortie : ',
                'widget' => 'single_text'
            ])
            ->add('maxNbRegistrations', NumberType::class, [
                'label' => 'Nombre de places : '
            ])
            ->add('infosActivity', TextareaType::class, [
                'label' => 'Description et infos : ',
                'attr' => [
                    'cols' => '30',
                    'rows' => '8'
                ]
            ])
//            ->add('participants')
//            ->add('organizer')
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'choice_label' => 'name',
                'class' => Campus::class,

            ])

            ->add('place', EntityType::class, [
                'label' => 'Lieu : ',
                'choice_label' => 'name',
                'class' => Place::class,

            ])
//            ->add('state')
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer les changements'
            ])
            ->add('saveAndPublish', SubmitType::class, [
                'label' => 'Publier la sortie'
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
