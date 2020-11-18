<?php

namespace Smart\AuthenticationBundle\Security\EventSubscriber;

use Smart\AuthenticationBundle\Security\LastLoginInterface;
use Smart\AuthenticationBundle\Security\Processor\LastLoginProcessor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@pia-production.fr>
 */
class SecuritySubscriber implements EventSubscriberInterface
{
    /**
     * @var LastLoginProcessor
     */
    private $lastLoginProcessor;

    public function __construct(LastLoginProcessor $lastLoginProcessor)
    {
        $this->lastLoginProcessor = $lastLoginProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::SWITCH_USER => 'onSwitchUser',
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }

    /**
     * @return void
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
        if (null !== $event->getRequest()->get('switch_user')) {
            return;
        }

        $this->lastLoginProcessor->process($user);
    }

    /**
     * @return void
     */
    public function onSwitchUser(SwitchUserEvent $event)
    {
        /** @var Session $session */
        $session = $event->getRequest()->getSession();
        $flashBag = $session->getFlashBag();

        if ('_exit' === $event->getRequest()->query->get('_switch_user')) {
            $flashBag->add('sonata_flash_success', 'impersonate.exit_message');
        } else {
            $flashBag->add('sonata_flash_success', 'impersonate.switch_message');
        }
    }
}
