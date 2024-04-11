<?php

namespace App\Form;

use App\Entity\Bien;
use App\Entity\Fournisseur;
use App\Entity\Maintenance;
use App\Entity\Notification;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class MaintenanceType extends AbstractType
{

    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Date and Time',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une date de début',
                    ]),
                ],
            ])
            ->add('dateT', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'End Date and Time',
                'constraints' => [
                    new GreaterThan([
                        'propertyPath' => 'parent.all[date].data',
                        'message' => 'La date et l\'heure de fin doivent être supérieures à la date et l\'heure de début.',
                    ]),
                ],
            ])
            ->add('details', TextareaType::class, [
                'label' => 'Détails',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer les détails de la maintenance',
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
                    'En cours' => 'En cours',
                    'Terminée' => 'Terminée',
                    'Echouée' => 'Echouée'
                    // Add more choices as needed
                ],
                'label' => 'État',
            ])
            ->add('prix', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le prix de la maintenance',
                    ]),
                ],
                'label' => 'Prix'
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Corrective' => 'Corrective',
                    'Préventive' => 'Préventive',
                ],
                'label' => 'Type',
            ])
            ->add('bien', EntityType::class, [
                'class' => Bien::class,
                'choice_label' => function ($bien) {return $bien->getId() . '-' . $bien->getNom();},
                'label' => 'Maintenance sur...',
                'placeholder' => 'Selectionnez une propriété...',
                'required' => true,
            ])
            ->add('users', EntityType::class, [
                'class' => User::class, // Replace with your User entity class
                'choice_label' => function ($user) {return $user->getPrenom() . ' ' . $user->getNom();}, // Adjust this to match your User entity property
                'label' => 'Utilisateurs',
                'multiple' => true, // Allow multiple selection
                'required' => false,
            ])
            ->add('fournisseurs', EntityType::class, [
                'class' => Fournisseur::class, // Replace with your Fournisseur entity class
                'choice_label' => function ($fournisseur) {return $fournisseur->getNom();}, // Adjust this to match your Fournisseur entity property
                'label' => 'Fournisseurs',
                'multiple' => true, // Allow multiple selection
                'required' => false,
            ]);
        $builder->add('validate_selection', HiddenType::class, [
            'mapped' => false,
            'constraints' => [
                new Callback([$this, 'validateSupplierUserSelection']),
            ],
        ]);

    }

    public function validateSupplierUserSelection($value, ExecutionContextInterface $context)
    {
        if (!isset($value['users']) || !isset($value['fournisseurs'])) {
            // One or both keys are not present in the array
            return;
        }

        $users = $value['users'];
        $fournisseurs = $value['fournisseurs'];

        if (empty($users) && empty($fournisseurs)) {
            $context->buildViolation('Veuillez choisir au moins un utilisateur ou un fournisseur.')
                ->atPath('validate_selection') // Choose an appropriate field path for violation
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Maintenance::class,
        ]);
    }
}
