<?php

namespace Smart\AuthenticationBundle\Controller\CRUD;

use Smart\AuthenticationBundle\Email\AccountCreationEmail;
use Smart\AuthenticationBundle\Security\SmartUserInterface;
use Smart\AuthenticationBundle\Security\Token;
use Smart\SonataBundle\Admin\AbstractAdmin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\SecurityTokenBundle\Manager\TokenManagerInterface;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 * @property AbstractAdmin $admin
 */
trait SendAccountCreationEmailTrait
{
    public function sendAccountCreationEmailAction(TokenManagerInterface $tokenManager, MailerInterface $mailer, TranslatorInterface $translator): Response
    {
        /** @var SmartUserInterface $subject */
        $subject = $this->admin->getSubject();

        $token = $tokenManager->create(Token::RESET_PASSWORD, $subject);
        $context = 'admin';

        $mailer->send(new AccountCreationEmail([
            'from' => $this->getParameter('app.mail_from'),
            'subject' => $translator->trans('security.user_created.subject', [], 'email'),
            'context' => $context,
            'token' => $token->getValue(),
            'domain' => $context . '.' . $this->getParameter('domain'),
            'security_reset_password_route' => $context . '_security_reset_password'
        ], $subject->getEmail()));

        $this->addFlash('success', $translator->trans('send_account_creation_email.success', [
            '{email}' => $subject->getEmail()
        ], 'admin'));

        return $this->redirect($this->admin->generateUrl('list'));
    }
}
