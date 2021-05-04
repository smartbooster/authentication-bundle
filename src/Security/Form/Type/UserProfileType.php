<?php

namespace Smart\AuthenticationBundle\Security\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 */
class UserProfileType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'form.label_email',
                'attr' => ['autocomplete' => 'off'],
            ])
            ->add('firstName', null, [
                'label' => 'form.label_first_name',
                'attr' => ['autocomplete' => 'off'],
            ])
            ->add('lastName', null, [
                'label' => 'form.label_last_name',
                'attr' => ['autocomplete' => 'off'],
            ])
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type'               => PasswordType::class,
                    'required'           => false,
                    'first_options'      => ['label' => 'form.label_password'],
                    'second_options'     => ['label' => 'form.label_password_confirmation'],
                    'translation_domain' => $options['translation_domain'],
                    'invalid_message' => 'reset_password.password_must_match',
                    'attr' => ['autocomplete' => 'off'],
                ]
            );
        ;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'translation_domain' => 'admin'
            ])
        ;
    }
}
