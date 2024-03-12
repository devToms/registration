<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('email', EmailType::class, [
        'constraints' => [
          new NotBlank([
            'message' => 'Please enter your email address.',
          ]),
          new Email([
            'message' => 'Please enter a valid email address.',
          ]),
        ],
      ])
      ->add('password', RepeatedType::class,[
        'type' => PasswordType::class,
        'first_options' => ['label' => 'Password'],
        'second_options' => ['label' => 'Repeat Password'],
        'constraints' => [
          new NotBlank([
            'message' => 'Please enter your password.'
          ]),
          new Length([
            'min' => 5,
            'minMessage' => 'The password should be at least {{ limit }} characters long.'
          ]),
          new Regex([
            'pattern'=>'/^(?=.*[A-Z])(?=.*\d.*).+$/',
            'message' => 'You still need a large character or number'
          ])
          ]
        ]
      )
      ->add('save', SubmitType::class);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
