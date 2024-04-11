<?php

namespace App\Form;

use App\Entity\Action;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class ActionType extends AbstractType
{

    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
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
                'label' => 'Message'
            ])
            ->add('iT', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer la valeur de l\'indicateur temporel',
                    ]),
                ],
                'label' => 'Indicateur temporel'
            ])
            ->add('iKm', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer la valeur de l\'indicateur en Km',
                    ]),
                ],
                'label' => 'Indicateur en Km'
            ]);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Action::class,
        ]);
    }
}
