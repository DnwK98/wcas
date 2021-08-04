<?php

declare(strict_types=1);

namespace App\User\Api\Form\Password;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordForm extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return '';
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('oldPassword', TextType::class);
        $builder->add('newPassword', TextType::class);
        $builder->add('newPasswordVerify', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PasswordRequest::class,
            'csrf_protection' => false,
        ]);
    }
}
