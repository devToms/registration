<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="user_registration")
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine,
        UserRepository $userRepository
    ): Response {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $existingUser = $userRepository->findByUserEmail($form->get('email')->getData());

            if ($existingUser) {
                $this->addFlash('warning', 'User with given e-mail address already exists.');
                return $this->redirectToRoute('user_registration');
            }

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );

            $user->setPassword($hashedPassword)->setRoles($user->getRoles());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_registration');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'RegistrationController',
            
        ]);
    }
}
