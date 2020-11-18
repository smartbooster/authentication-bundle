<?php

namespace Smart\AuthenticationBundle\Admin\Extension;

use Smart\AuthenticationBundle\Security\SmartUserInterface;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@pia-production.fr>
 */
class EncodePasswordExtension extends AbstractAdminExtension
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate(AdminInterface $admin, $user): void
    {
        $this->encodePassword($user);
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(AdminInterface $admin, $user): void
    {
        $this->encodePassword($user);
    }

    private function encodePassword(SmartUserInterface $user): void
    {
        if ('' === trim($user->getPlainPassword())) {
            return;
        }

        $user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()));
    }
}
