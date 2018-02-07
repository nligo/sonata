<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/admin/login",name="admin_login")
     */
    public function loginAction()
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect('/admin');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one / 获取可能存在的登录错误信息
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user / 获取用户输入的username（用户名）
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('AdminBundle::login.html.twig', [
                'username' => $lastUsername,
                'error' => $error,
            ]
        );
    }
}
