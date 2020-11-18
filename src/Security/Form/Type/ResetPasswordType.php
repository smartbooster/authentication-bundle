<?php

namespace Smart\AuthenticationBundle\Security\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Nicolas Bastien <nicolas.bastien@smartbooster.io>
 */
class ResetPasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'password',
                RepeatedType::class,
                [
                    'property_path' => 'plainPassword',
                    'type' => PasswordType::class,
                    'required' => true,
                    'invalid_message' => 'reset_password.password_must_match',
                    'first_options' => ['label' => false],
                    'second_options' => ['label' => false],
                ]
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'translation_domain' => 'admin',
            ])
        ;
    }
}
