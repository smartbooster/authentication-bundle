<?php

namespace Smart\AuthenticationBundle\Form\Type\Security;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Nicolas Bastien <nicolas.bastien@smartbooster.io>
 */
class ForgotPasswordType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'forgot_password.label_email',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ]
            ])
        ;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'security',
        ]);
    }
}
