<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Place;

use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
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

//Ajouts tuto
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
class ChangeActivityType extends AbstractType
{
    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
//        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie',
            ])
            ->add('dateTimeBeginning', DateTimeType::class, [
                'label' => 'Date et heure de la sortie : ',
                'date_widget' => 'single_text',
                'time_widget' => 'text',
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée : '
            ])
            ->add('dateLimitRegistration', DateType::class, [
                'label' => 'Date limite d\'inscription : ',
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
//                'data' => 'campus'
            ])

            ->add('place', EntityType::class, [
                'label' => 'Lieu : ',
                'choice_label' => 'name',
                'class' => Place::class,
//                'attr' => [
//                    'id' => 'activity_place'
//                ]
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

//    protected function addElements(FormInterface $form, Place $place = null) {
//        // 4. Add the city element
//        $form->add('place', EntityType::class, array(
//            'required' => true,
//            'data' => $place,
//            'class' => Place::class
//        ));
//        // Neighborhoods empty, unless there is a selected City (Edit View)
//        $cities = array();
//
//        // If there is a city stored in the Person entity, load the neighborhoods of it
//        if ($place) {
//            // Fetch Neighborhoods of the City if there's a selected city
//            $cityRepository = $this->em->getRepository(CityRepository::class);
//
//            $cities = $cityRepository->createQueryBuilder("q")
//                ->where("q.place = :placeId")
//                ->setParameter("placeId", $place->getId())
//                ->getQuery()
//                ->getResult();
//        }
//        // Add the Neighborhoods field with the properly data
//        $form->add('city', EntityType::class, array(
//            'required' => true,
//            'placeholder' => 'Sélectionnez un lieu',
//            'class' => City::class,
//            'choices' => $cities
//        ));
//    }
//
//    function onPreSubmit(FormEvent $event) {
//        $form = $event->getForm();
//        $data = $event->getData();
//
//        // Search for selected City and convert it into an Entity
//        $place = $this->em->getRepository(PlaceRepository::class)->find($data['place']);
//
//        $this->addElements($form, $place);
//    }
//
//    function onPreSetData(FormEvent $event) {
//        $place = $event->getData();
//        $form = $event->getForm();
//
//        // When you create a new person, the City is always empty
//        $city = $place->getCity() ? $place->getCity() : null;
//
//        $this->addElements($form, $city);
//    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
//    /**
//     * {@inheritdoc}
//     */
//    public function getBlockPrefix()
//    {
//        return 'app_place_city';
//    }
}
