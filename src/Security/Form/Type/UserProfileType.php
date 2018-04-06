<?php

namespace Smart\AuthenticationBundle\Security\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@pia-production.fr>
 */
class UserProfileType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'user.label_email'
            ])
            ->add('firstName', null, [
                'label' => 'user.label_first_name'
            ])
            ->add('lastName', null, [
                'label' => 'user.label_last_name'
            ])
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type'               => 'password',
                    'required'           => false,
                    'first_options'      => ['label' => 'user.label_password'],
                    'second_options'     => ['label' => 'user.label_password_confirmation'],
                    'translation_domain' => $options['translation_domain'],
                ]
            );
        ;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'translation_domain' => 'admin'
            ])
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'user_profile';
    }
}
