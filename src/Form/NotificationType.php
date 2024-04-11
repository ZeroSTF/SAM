<?php

namespace App\Form;

use App\Entity\Notification;
use App\Entity\User;
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


class NotificationType extends AbstractType
{

    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un message',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 555,
                        'minMessage' => 'Le message doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le message doit contenir au maximum {{ limit }} caractères',
                    ]),
                ],
                'label' => 'Message'
            ])
            ->add('user', EntityType::class, [
                'class' => User::class, // Replace with the actual User entity class
                'choice_label' => function ($user) {return $user->getPrenom() . ' ' . $user->getNom();}, // Replace with the actual property name for displaying the user name
                'label' => 'Envoyer à...', // Label for the field
                'placeholder' => 'Selectionnez un utilisateur', // Placeholder text when no user is selected
                'required' => true, // Set to true if a user selection is mandatory
                // Additional options as needed
            ]);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notification::class,
        ]);
    }
}
