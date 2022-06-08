<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Campus;
use App\Form\Model\SearchActivityModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class SearchType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

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
            ->add('minDateTimeBeginning', DateType::class, [
                'label' => 'Entre',
                'required' => false,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',

            ])
            ->add('maxDateTimeBeginning', DateType::class, [
                'label' => 'et',
                'required' => false,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])

            ->add('participantCampus', EntityType::class, [
                'label' => 'Campus : ',
                'choice_label' => 'name',
               'class' => Campus::class,

                'required' => false,
               'data' => $this->security->getUser()->getCampus()
//               'data' => 'participantCampus'

            ])
            ->add('filterActiOrganized', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false
            ])
            ->add('filterActiJoined', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false
            ])
            ->add('filterActiNotJoined', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false
            ])
            ->add('filterActiEnded', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => [
                    'name' => 'searchButton'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchActivityModel::class,

        ]);
    }
}
