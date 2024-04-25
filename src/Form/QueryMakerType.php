<?php

namespace App\Form;

use App\Entity\QueryMakerCSV;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class QueryMakerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('table_name', TextType::class, [
                'label' => 'Table Name',
                'required' => true,
                'attr' => ['placeholder' => 'Enter Table Name'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'string']),
                    new Assert\Length(['min' => 2, 'max' => 100])
                ],
            ])
            ->add('csvFile', FileType::class, [
                'label' => 'Upload CSV File',
                'required' => true,
                'mapped' => false,
                'attr' => ['accept' => '.csv'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\File([
                        'extensions' => [
                            'csv',
                        ],
                        'extensionsMessage' => 'Please upload a valid CSV file',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QueryMakerCSV::class,
        ]);
    }
}
