<?php

// src/Form/TaskType.php
namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('task', TextType::class, [
                'label' => 'Task Title',
                'required' => true,
                'attr' => ['class' => 'text-danger']
            ])
            ->add('dueDate', DateType::class, [
                'attr' => ['class' => 'js-datepicker'],
                'widget' => 'single_text',
                //'html5' => false //default: true
            ])
            ->add('agreeTerms', CheckboxType::class, ['mapped' => false])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}