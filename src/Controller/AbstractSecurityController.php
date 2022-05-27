<?php

namespace Smart\AuthenticationBundle\Controller;

use Smart\AuthenticationBundle\Security\Form\Type\ResetPasswordType;
use Smart\AuthenticationBundle\Security\Form\Type\UserProfileType;
use Smart\AuthenticationBundle\Form\Type\Security\ForgotPasswordType;
use Smart\AuthenticationBundle\Security\SmartUserInterface;
use Smart\AuthenticationBundle\Security\Token;
use Smart\SonataBundle\Mailer\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Smart\SonataBundle\Mailer\BaseMailer;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Yokai\SecurityTokenBundle\Exception\TokenNotFoundException;
use Yokai\SecurityTokenBundle\Exception\TokenConsumedException;
use Yokai\SecurityTokenBundle\Exception\TokenExpiredException;
use Yokai\SecurityTokenBundle\Manager\TokenManagerInterface;

/**
 * @author Nicolas Bastien <nicolas.bastien@smartbooster.io>
 *
 * Fix phpstan error cf. https://github.com/phpstan/phpstan/issues/3200
 * @property ContainerInterface $container
 */
class AbstractSecurityController extends Controller
{
    /**
     * Define application context, override this in your controller
     * @var string
     */
    protected $context;

    /**
     * @var TokenManagerInterface
     */
    protected $tokenManager;

    /**
     * @var BaseMailer
     */
    protected $mailer;

    public function __construct(TokenManagerInterface $tokenManager, BaseMailer $mailer)
    {
        $this->tokenManager = $tokenManager;
        $this->mailer = $mailer;
    }

    /**
     * @deprecated
     * @return Response
     */
    public function loginAction()
    {
        return $this->login();
    }

    /**
     * @deprecated
     * @param Request $request
     *
     * @return Response
     */
    public function forgotPasswordAction(Request $request)
    {
        return $this->forgotPassword($request);
    }

    /**
     * @deprecated
     * @param Request $request
     *
     * @return Response
     */
    public function resetPasswordAction(Request $request)
    {
        return $this->resetPassword($request);
    }

    /**
     * @deprecated
     * @param Request $request
     *
     * @return Response
     */
    public function profileAction(Request $request)
    {
        return $this->profile($request);
    }

    /**
     * @return Response
     */
    public function login()
    {
        $helper = $this->getAuthenticationUtils();

        return $this->render($this->context . '/security/login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
            'layout_template' => $this->context . '/empty_layout.html.twig',
            'security_login_check_url' => $this->generateUrl($this->context . '_security_login_check'),
            'security_forgot_password_url' => $this->generateUrl($this->context . '_security_forgot_password'),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function forgotPassword(Request $request)
    {
        $form =  $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                $this->context . '/security/forgot_password.html.twig',
                [
                    'form' => $form->createView(),
                    'security_login_form_url' => $this->generateUrl($this->context . '_security_login_form'),
                    'security_forgot_password_url' => $this->generateUrl($this->context . '_security_forgot_password'),
                ]
            );
        }

        try {
            $user = $this->get($this->context . '_user_provider')->loadUserByUsername($form->get('email')->getData());

            if ($user instanceof SmartUserInterface) {
                $token = $this->tokenManager->create(Token::RESET_PASSWORD, $user);

                $email = $this->mailer->newEmail($this->context . '.security.forgot_password', [
                    'domain' => $this->getDomain(),
                    'security_reset_password_route' => $this->context . '_security_reset_password',
                    'token' => $token->getValue(),
                ]);
                $this->mailer->send($email, $user->getEmail());

                $this->addFlash('success', 'flash.forgot_password.success');
            }
        } catch (UsernameNotFoundException $e) {
            $this->addFlash('error', 'flash.forgot_password.unknown');

            return $this->redirectToRoute($this->context . '_security_forgot_password');
        }

        return $this->redirectToRoute($this->context . '_security_login_form');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function resetPassword(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute($this->context . '_dashboard');
        }

        if (!$request->query->has('token')) {
            $this->addFlash('error', 'flash.security.invalid_token');

            return $this->redirectToRoute($this->context . '_security_login_form');
        }

        try {
            $token = $this->tokenManager->get(Token::RESET_PASSWORD, $request->query->get('token'));
        } catch (TokenNotFoundException $e) {
            $this->addFlash('error', 'flash.security.token_not_found');
            return $this->redirectToRoute($this->context . '_security_login_form');
        } catch (TokenExpiredException $e) {
            $this->addFlash('error', 'flash.security.token_expired');
            return $this->redirectToRoute($this->context . '_security_login_form');
        } catch (TokenConsumedException $e) {
            $this->addFlash('error', 'flash.security.token_used');
            return $this->redirectToRoute($this->context . '_security_login_form');
        }

        /** @var SmartUserInterface $user */
        $user = $this->tokenManager->getUser($token);

        $form =  $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                $this->context . '/security/reset_password.html.twig',
                [
                    'token' => $token->getValue(),
                    'form' => $form->createView(),
                    'security_reset_password_route' => $this->context . '_security_reset_password'
                ]
            );
        }

        try {
            if (null !== $user->getPlainPassword()) {
                $this->updateUser($user);
                $this->tokenManager->consume($token);
            }
            $this->addFlash('success', 'flash.reset_password.success');
        } catch (\Exception $e) {
            $this->addFlash('error', 'flash.reset_password.error');
        }

        return $this->redirectToRoute($this->context . '_security_login_form');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function profile(Request $request)
    {
        /** @var SmartUserInterface $user */
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

        $this->updateUser($user);

        $this->addFlash('success', $this->translate('profile_edit.processed', [], 'security'));

        return $this->redirectToRoute('sonata_admin_dashboard');
    }

    /**
     * @return AuthenticationUtils
     */
    private function getAuthenticationUtils()
    {
        return $this->get('security.authentication_utils');
    }

    /**
     * @param string      $id         The message id (may also be an object that can be cast to string)
     * @param array<array>       $parameters An array of parameters for the message
     * @param string|null $domain     The domain for the message or null to use the default
     *
     * @return string
     */
    protected function translate($id, array $parameters = array(), $domain = null)
    {
        return $this->get('translator')->trans($id, $parameters, $domain);
    }

    /**
     * @param SmartUserInterface $user
     *
     * @return void
     */
    protected function updateUser(SmartUserInterface $user)
    {
        if (null !== $user->getPlainPassword()) {
            $encoder = $this->get('security.password_encoder');
            $user->setPassword(
                $encoder->encodePassword($user, $user->getPlainPassword())
            );
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();
    }

    /**
     * Override this method if your application use custom domain
     *
     * @return string
     */
    protected function getDomain()
    {
        return $this->container->getParameter('domain');
    }
}
