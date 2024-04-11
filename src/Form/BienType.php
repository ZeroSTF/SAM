<?php

namespace App\Form;

use App\Entity\Bien;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class BienType extends AbstractType
{


    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un nom',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 555,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom doit contenir au maximum {{ limit }} caractères',
                    ]),
                ],
                'label' => 'Nom'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une description',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 555,
                        'minMessage' => 'La description doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'La description doit contenir au maximum {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Disponible' => 'Disponible',
                    'Loué' => 'Loué',
                    'En panne' => 'En panne',
                    'Fonctionnel' => 'Fonctionnel',
                    // Add more choices as needed
                ],
                'label' => 'État',
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Extérieur Fixe' => 'Extérieur Fixe',
                    'Extérieur Mobile' => 'Extérieur Mobile',
                    'Intérieur' => 'Intérieur',
                    // Add more choices as needed
                ],
                'label' => 'Type',
            ])
            ->add('bien', EntityType::class, [
                'class' => Bien::class, // Replace with the actual User entity class
                'choice_label' => function ($bien) {return $bien->getId() . '-' . $bien->getNom();}, // Replace with the actual property name for displaying the user name
                'label' => 'Compose...', // Label for the field
                'placeholder' => 'Compose...', // Placeholder text when no user is selected
                'required' => false, // Set to true if a user selection is mandatory
            ])
            ->add('x', IntegerType::class, [
                'label' => 'X'
            ])
            ->add('y', IntegerType::class, [
                'label' => 'Y'
            ]);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bien::class,
        ]);
    }
}
