<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    public function login(Request $request, AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $lastEmail = $utils->getLastUsername();
        
        return $this->render(
            'login_form.html.twig',
            [
                'last_email' => $lastEmail,
                'error' => $error,
            ]
        );
    }

    public function logout(): void
    {
        /*return $this->json(
            [
                'redirectURL' => $this->generateUrl('loginView')
            ]
        );*/
    }
}