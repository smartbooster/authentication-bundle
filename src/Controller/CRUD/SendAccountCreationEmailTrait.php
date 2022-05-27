<?php

namespace Smart\AuthenticationBundle\Controller\CRUD;

use Smart\AuthenticationBundle\Security\SmartUserInterface;
use Smart\AuthenticationBundle\Security\Token;
use Smart\SonataBundle\Admin\AbstractAdmin;
use Symfony\Component\HttpFoundation\Response;
use Smart\SonataBundle\Mailer\BaseMailer;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\SecurityTokenBundle\Manager\TokenManagerInterface;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 * @property AbstractAdmin $admin
 */
trait SendAccountCreationEmailTrait
{
    public function sendAccountCreationEmailAction(TokenManagerInterface $tokenManager, BaseMailer $mailer, TranslatorInterface $translator): Response
    {
        /** @var SmartUserInterface $subject */
        $subject = $this->admin->getSubject();

        $token = $tokenManager->create(Token::RESET_PASSWORD, $subject);
        $context = 'admin';

        $email = $mailer->newEmail('admin.security.account_creation', [
            'domain' => $this->getParameter('domain'),
            'context' => $context,
            'security_reset_password_route' => $context . '_security_reset_password',
            'token' => $token->getValue(),
        ]);
        $mailer->send($email, $subject->getEmail());

        $this->addFlash('success', $translator->trans('send_account_creation_email.success', [
            '{email}' => $subject->getEmail()
        ], 'admin'));

        return $this->redirect($this->admin->generateUrl('list'));
    }
}
