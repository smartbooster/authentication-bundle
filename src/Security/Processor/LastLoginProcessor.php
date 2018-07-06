<?php

namespace Smart\AuthenticationBundle\Security\Processor;

use DateTime;
use Doctrine\ORM\EntityManager;
use Smart\AuthenticationBundle\Entity\User\UserTrait;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@pia-production.fr>
 */
class LastLoginProcessor
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $object
     */
    public function process($object)
    {
        if (!isset(class_uses($object)[UserTrait::class])) {
            return;
        }

        $object->setLastLogin(new DateTime());
        $this->em->persist($object);
        $this->em->flush();
    }
}
