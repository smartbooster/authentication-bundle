<?php

namespace Smart\AuthenticationBundle\DataFixtures\Processor;

use Fidry\AliceDataFixtures\ProcessorInterface;
use Smart\AuthenticationBundle\Security\SmartUserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Nicolas Bastien <nicolas.bastien@smartbooster.io>
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
     * @param mixed $object
     */
    public function preProcess(string $fixtureId, $object): void
    {
        if (!$object instanceof SmartUserInterface) {
            return;
        }

        $object->setPassword($this->encoder->encodePassword($object, $object->getPlainPassword()));
    }

    /**
     * @inheritdoc
     * @param mixed $object
     */
    public function postProcess(string $fixtureId, $object): void
    {
    }
}
