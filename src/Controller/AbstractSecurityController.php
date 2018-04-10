<?php

namespace Smart\AuthenticationBundle\Controller;

use Smart\AuthenticationBundle\Security\Form\Type\UserProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
     * @param string      $id         The message id (may also be an object that can be cast to string)
     * @param array       $parameters An array of parameters for the message
     * @param string|null $domain     The domain for the message or null to use the default
     *
     * @return string
     */
    protected function translate($id, array $parameters = array(), $domain = null)
    {
        return $this->get('translator')->trans($id, $parameters, $domain);
    }
    
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

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function profileAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserProfileType::class, $user, []);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render($this->context . '/security/profile.html.twig', [
                'base_template' => $this->get('sonata.admin.pool')->getTemplate('layout'),
                'admin_pool'    => $this->get('sonata.admin.pool'),
                'form'          => $form->createView(),
                'security_profile_url' => $this->generateUrl('admin_security_profile'),
            ]);
        }

        if (null !== $user->getPlainPassword()) {
            $encoder = $this->get('security.password_encoder');
            $user->setPassword(
                $encoder->encodePassword($user, $user->getPlainPassword())
            );
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();

        $this->addFlash('success', $this->translate('profile_edit.processed', [], 'security'));

        return $this->redirectToRoute('sonata_admin_dashboard');
    }
}
