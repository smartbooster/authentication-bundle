<?php

namespace Smart\AuthenticationBundle\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Header\Headers;

class ForgotPasswordEmail extends TemplatedEmail
{
    /**
     * ForgotPasswordEmail constructor.
     *
     * @param array<mixed> $parameters
     * @param string       $email
     */
    public function __construct($parameters, $email)
    {
        parent::__construct(new Headers(), null);

        $this->from($parameters['from'])
            ->to(new Address($email))
            ->subject($parameters['subject'])
            ->htmlTemplate(sprintf('email/%s/security/forgot_password.html.twig', $parameters['context']))
            ->context($parameters)
        ;
    }
}
