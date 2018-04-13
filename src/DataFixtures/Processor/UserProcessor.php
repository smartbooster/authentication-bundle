<?php

namespace Smart\AuthenticationBundle\DataFixtures\Processor;

use Nelmio\Alice\ProcessorInterface;
use Smart\AuthenticationBundle\Security\SmartUserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Nicolas Bastien <nicolas.bastien@smartbooster.io>
 */
class UserProcessor implements ProcessorInterface
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
     * @inheritdoc
     */
    public function preProcess($object)
    {
        if (!$object instanceof SmartUserInterface) {
            return;
        }

        $object->setPassword($this->encoder->encodePassword($object, $object->getPlainPassword()));
    }

    /**
     * @inheritdoc
     */
    public function postProcess($object)
    {
    }
}
