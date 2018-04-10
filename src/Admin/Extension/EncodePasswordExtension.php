<?php

namespace Smart\AuthenticationBundle\Admin\Extension;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@pia-production.fr>
 */
class EncodePasswordExtension extends AbstractAdminExtension
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritDoc}
     */
    public function preUpdate(AdminInterface $admin, $user)
    {
        $this->encodePassword($user);
    }

    /**
     * {@inheritDoc}
     */
    public function prePersist(AdminInterface $admin, $user)
    {
        $this->encodePassword($user);
    }

    /**
     * @param UserInterface $user
     */
    private function encodePassword(UserInterface $user)
    {
        if ("" === trim($user->getPlainPassword())) {
            return;
        }

        $user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()));
    }
}
