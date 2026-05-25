<?php

namespace App\Controller;

use App\DTO\RegisterDTO;
use App\Form\RegistrationFormType;
use App\Service\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_auth')]
    public function index(
        Request $request,
        RegisterService $service,
        AuthenticationUtils $authenticationUtils
    ): Response {

        $registerDto = new RegisterDTO();
        $registerForm = $this->createForm(RegistrationFormType::class, $registerDto);

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'form' => $registerForm->createView(),
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(
        Request $request,
        RegisterService $service,
    ): Response {
        $registerDto = new RegisterDTO();
        $registerForm = $this->createForm(RegistrationFormType::class, $registerDto);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $service->register($registerDto);
            return $this->redirectToRoute('app_auth');
        }

        return $this->redirectToRoute('app_auth');
    }

    #[Route('/auth/show', name: 'auth_show')]
    public function show(): Response
    {
        return $this->render('auth/show.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void {}
}