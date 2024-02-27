<?php

// src/Form/TaskType.php
namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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

//        $builder
//            ->add('condition_field', ChoiceType::class, [
//                'choices' => [
//                    'Select' => '',
//                    'Option 1' => 'option1',
//                    'Option 2' => 'option2',
//                ],
//                'required' => true,
//                'mapped' => false
//            ])
//            ->add('conditional_field', TextType::class, [
//                'required' => false,
//                'mapped' => false
//            ]);
//
//        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
//            $data = $event->getData();
//            $form = $event->getForm();
//
//            // Check if condition_field is set to a specific value
//            if ($data['condition_field'] === 'option1') {
//                // Add or remove conditional_field based on the condition
//                $form->add('conditional_field', TextType::class, [
//                    'required' => true,
//                    'mapped' => false
//                ]);
//            } else {
//                $form->remove('conditional_field');
//            }
//        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}