<?php

namespace Smart\AuthenticationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @author Nicolas Bastien <nicolas.bastien@smartbooster.io>
 */
class AbstractSecurityController extends Controller
{
    /**
     * Define application context, override this in your controller
     * @var string
     */
    protected $context;
    
    /**
     * @return Response
     */
    public function loginAction()
    {
        $helper = $this->getAuthenticationUtils();

        return $this->render($this->context . '/security/login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
            'layout_template' => $this->context . '/empty_layout.html.twig',
            'security_login_check_url' => $this->generateUrl($this->context . '_security_login_check'),
//            'security_forgot_password_url' => $this->generateUrl($this->context . '_security_forgot_password'),
        ]);
    }

    /**
     * @return AuthenticationUtils
     */
    private function getAuthenticationUtils()
    {
        return $this->get('security.authentication_utils');
    }
}
