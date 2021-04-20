<?php

namespace Smart\AuthenticationBundle\Security\EventSubscriber;

use Smart\AuthenticationBundle\Security\LastLoginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Smart\AuthenticationBundle\Security\Processor\LastLoginProcessor;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param LastLoginProcessor $lastLoginProcessor
     */
    public function __construct(LastLoginProcessor $lastLoginProcessor, TranslatorInterface $translator)
    {
        $this->lastLoginProcessor = $lastLoginProcessor;
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::SWITCH_USER => 'onSwitchUser',
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }

    /**
     * @param InteractiveLoginEvent $event
     *
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
        if ($event->getRequest()->get('switch_user') !== null) {
            return;
        }

        $this->lastLoginProcessor->process($user);
    }

    /**
     * @param SwitchUserEvent $event
     *
     * @return void
     */
    public function onSwitchUser(SwitchUserEvent $event)
    {
        /** @var Session $session */
        $session = $event->getRequest()->getSession();
        $flashBag = $session->getFlashBag();

        if ($event->getRequest()->query->get('_switch_user') == '_exit') {
            $flashBag->add('sonata_flash_success', $this->translator->trans('impersonate.exit_message'));
        } else {
            $flashBag->add('sonata_flash_success', $this->translator->trans('impersonate.switch_message'));
        }
    }
}
