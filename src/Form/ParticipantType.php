<?php

namespace App\Form;

use App\Entity\Participant;

use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,[
                'label' =>"Pseudo :"
            ])
            ->add('firstname', TextType::class,[
                'label' =>"Prénom :"
            ])
            ->add('name', TextType::class,[
                'label' =>"Nom :"
            ])
            ->add('phone', TextType::class,[
                'label' =>"Téléphone :"
            ])

            ->add('email', TextType::class,[
                'label' =>"Email :"
            ])
            ->add('password', PasswordType::class,[
                'label' =>"Mot de passe :"
            ])
            ->add('campus', EntityType::class,[
                'label' =>"Campus :",
                'choice_label' =>"name",
                'class' => 'App\Entity\Campus'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
