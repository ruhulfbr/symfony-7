<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'string']),
                    new Assert\Length(['min' => 2, 'max' => 100])
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email()
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 6, 'max' => 30])
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER'
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new Assert\Callback([$this, 'validateUserData'])
            ]
        ]);
    }

    public function validateUserData($user, ExecutionContextInterface $context): void
    {
        $this->validateNameLength($user, $context);
        $this->validateEmailLength($user, $context);
    }

    private function validateNameLength($user, ExecutionContextInterface $context): void
    {
        $name = $user->getName();
        if (!$name) {
            return;
        }

        if (strlen($name) < 5) {
            $context->buildViolation('The name field must be at least 5 characters long.')
                ->atPath('name')
                ->addViolation();
        }

        if (strlen($name) > 10) {
            $context->buildViolation('The name field must be smaller than 10 characters.')
                ->atPath('name')
                ->addViolation();
        }
    }

    private function validateEmailLength($user, ExecutionContextInterface $context): void
    {
        $email = $user->getEmail();
        if (!$email) {
            return;
        }

        // Split email address into username and domain
        [$username, $domain] = explode('@', $email);

        if ($domain === 'gmail.com' && strlen($username) < 10) {
            $context->buildViolation('For Gmail addresses, the username must be at least 10 characters long.')
                ->atPath('email')
                ->addViolation();
        } elseif (strlen($email) < 20) {
            $context->buildViolation('For non-Gmail addresses, the email must be at least 20 characters long.')
                ->atPath('email')
                ->addViolation();
        }
    }
}
