<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\RegistrationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;

class RegistrationController extends AbstractController
{
    private RegistrationService $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * @Route("/registration", name="user_registration")
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if (!$this->registrationService->registerUser($user)) {
                    $this->addFlash('warning', 'User with given e-mail address already exists.');
                } else {
                    $this->addFlash('success', 'Registration successful!');
                    return $this->redirectToRoute('login_route');
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'An error occurred during registration.');
            }
        }
    
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'RegistrationController',
        ]);
    }
}
