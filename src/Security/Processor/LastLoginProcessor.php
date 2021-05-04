<?php

namespace Smart\AuthenticationBundle\Security\Processor;

use DateTime;
use Doctrine\ORM\EntityManager;
use Smart\AuthenticationBundle\Security\LastLoginInterface;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
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
     * @param LastLoginInterface $object
     *
     * @return void
     */
    public function process($object)
    {
        if (!$object instanceof LastLoginInterface) {
            return;
        }

        $object->setLastLogin(new DateTime());
        $this->em->persist($object);
        $this->em->flush();
    }
}
