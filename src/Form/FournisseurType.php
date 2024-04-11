<?php

namespace App\Form;

use App\Entity\Bien;
use App\Entity\Fournisseur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;


class FournisseurType extends AbstractType
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
            ->add('numTel', TextType::class, [
                'required' => false,
                'label' => 'Numero de Telephone',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le numéro de téléphone.',
                    ]),
                    new Length([
                        'min' => 8,
                        'max' => 8,
                        'exactMessage' => 'Le numéro de téléphone doit comporter exactement {{ limit }} chiffres.',
                    ]),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Le numéro de téléphone doit contenir uniquement des chiffres.',
                    ]),
                ],
            ]);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fournisseur::class,
        ]);
    }
}
