<?php

namespace Smart\AuthenticationBundle\Security\EventSubscriber;

use Smart\AuthenticationBundle\Security\LastLoginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Smart\AuthenticationBundle\Security\Processor\LastLoginProcessor;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@pia-production.fr>
 */
class SecuritySubscriber implements EventSubscriberInterface
{
    /**
     * @var LastLoginProcessor
     */
    private $lastLoginProcessor;

    /**
     * @param LastLoginProcessor $lastLoginProcessor
     */
    public function __construct(LastLoginProcessor $lastLoginProcessor)
    {
        $this->lastLoginProcessor = $lastLoginProcessor;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin'
        ];
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $token = $event->getAuthenticationToken();

        if (!$token instanceof TokenInterface) {
            return;
        }

        $user = $token->getUser();

        if (!$user instanceof LastLoginInterface) {
            return;
        }

        // Impersonnate user must not update last_login date
        if ($event->getRequest()->get('switch_user') !== null) {
            return;
        }

        $this->lastLoginProcessor->process($user);
    }
}
