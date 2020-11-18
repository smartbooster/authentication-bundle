<?php

namespace Smart\AuthenticationBundle\Security\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@pia-production.fr>
 */
class UserProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'form.label_email',
            ])
            ->add('firstName', null, [
                'label' => 'form.label_first_name',
            ])
            ->add('lastName', null, [
                'label' => 'form.label_last_name',
            ])
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'required' => false,
                    'first_options' => ['label' => 'form.label_password'],
                    'second_options' => ['label' => 'form.label_password_confirmation'],
                    'translation_domain' => $options['translation_domain'],
                    'invalid_message' => 'reset_password.password_must_match',
                ]
            );
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
