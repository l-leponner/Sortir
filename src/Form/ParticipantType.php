<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,[
                'label' =>"Pseudo :",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un pseudo.',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre pseudo doit contenir au moins {{ limit }} caractères.',
                        'max' => 40,
                        'maxMessage' => 'Votre pseudo doit contenir au maximum {{ limit }} caractères.',
                    ]),
                    ]
            ])
            ->add('firstname', TextType::class,[
                'label' =>"Prénom : ",
                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Merci de renseigner un prénom.',
//                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre prénom doit contenir au moins {{ limit }} caractères.',
                        'max' => 40,
                        'maxMessage' => 'Votre prénom doit contenir au maximum {{ limit }} caractères.',
                    ]),
                    ]
            ])
            ->add('name', TextType::class,[
                'label' =>"Nom : ",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un nom.',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom doit contenir au moins {{ limit }} caractères.',
                        'max' => 40,
                        'maxMessage' => 'Votre nom doit contenir au maximum {{ limit }} caractères.',
                    ]),
                ]
            ])
            ->add('phone', TextType::class,[
                'label' =>"Téléphone : ",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un numéro de téléphone.',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Votre numéro de téléphone doit contenir au moins {{ limit }} caractères.',
                        'max' => 40,
                        'maxMessage' => 'Votre numéro de téléphone doit contenir au maximum {{ limit }} caractères.',
                    ]),
                ]
            ])


            ->add('email', TextType::class,[
                'label' =>"Email : ",
                'constraints' => [
                    new Email([
                        'message' => 'L\'adresse email {{ value }} n\'est pas une adresse email valide.'
                    ]),
                    new NotBlank([
                        'message' => 'Merci de renseigner une adresse email.'
                    ]),
                    ]
            ])
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe ne correspond pas à sa confirmation.',
                'first_options' => [
                    'label' => 'Mot de passe : ',
//                   'help' => 'Le mot de passe doit contenir au minimum 8 caractères dont une minuscule, une majuscule, un chiffre et un caractère spécial.',
                ],
                'second_options' => [
                    'label' => 'Confirmation : ',
                ],
                'mapped' => false,
                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Veuillez renseigner un mot de passe.',
//                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
//                         max length allowed by Symfony for security reasons
                        'max' => 255,
                        'maxMessage' => 'Votre mot de passe doit contenir au maximum {{ limit }} caractères.'
                    ]),
                    new Regex([
                        'pattern' => "/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[ !\"\#\$%&\'\(\)*+,\-.\/:;<=>?@[\\^\]_`\{|\}~])^.{0,4096}$/",
                        'message' => 'Le mot de passe doit contenir obligatoirement une minuscule, une majuscule, un chiffre et un caractère spécial.',
                    ])
                ]
            ])

            ->add('campus', EntityType::class,[
                'label' =>"Campus : ",
                'choice_label' =>"name",
                'class' => 'App\Entity\Campus'
            ])
            ->add('save', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'btn mt-3 cardButton col-12',
                'id' => 'loginBtn'
            ]
           ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ]);
    }
}
