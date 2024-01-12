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
    private $registrationService;

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
            if (!$this->registrationService->registerUser($user)) {
                $this->addFlash('warning', 'User with given e-mail address already exists.');
                return $this->redirectToRoute('user_registration');
            }

            return $this->redirectToRoute('user_registration');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'RegistrationController',
        ]);
    }
}
