<?php

declare(strict_types=1);

namespace App\Controller;

use App\ServiceProvider;
use App\User\API\UserAPI;
use App\User\App\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{

    private UserAPI $userAPI;
    public function __construct(
        ServiceProvider $provider
    ) 
    {
        $this->userAPI = $provider->getUserAPI();
    }

    public function index(): Response
    {
        return $this->render(
            'register_form.html.twig'
        );
    }

    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $roles = ['CUSTOMER', 'SELLER', 'ADMIN'];
        $role = $request->get('role');
        if($role === null || !in_array($role, $roles))
        {
            return new Response('Незаполненное обязательное поле в форме', Response::HTTP_BAD_REQUEST);
        }
        $user = new User(
            null,
            $request->get('email'),
            $request->get('password'),
            0,
            $role
        );
        $user->setPassword(
            $passwordHasher->hashPassword($user, $user->getPassword())
        );
        $this->userAPI->createUser($user);
        return $this->redirectToRoute(
            'loginView'
        );
    }
}