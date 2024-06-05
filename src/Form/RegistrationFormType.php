<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;




class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_prenom', null, [
                'label' => 'Nom et Prénom',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ nom et prénom ne peut pas être vide.',
                    ]),
                ],
            ])
            ->add('telephone', null, [
                'label' => 'Numéro de téléphone',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Saisissez votre numéro de téléphone ici'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ numéro de téléphone ne peut pas être vide.',
                    ]),
                    // new Positive([
                    //     'message' => 'Le numéro de téléphone doit être un nombre positif.',
                    // ]),
                    new Regex([
                        'pattern' => '/^\d{8}$/',
                        'message' => 'Le numéro de téléphone doit être formé de 8 chiffres exactement.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ adresse e-mail ne peut pas être vide.',
                    ]),
                    new Email([
                        'message' => 'L\'adresse e-mail "{{ value }}" n\'est pas valide(le mail doit être sous forme"exemple@exemple.exemple" ',
                        'mode' => 'strict',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les termes et conditions.',
                    ]),
                ],
                'label' => 'J\'accepte les termes et conditions',
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ mot de passe ne peut pas être vide.',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/',
                        'message' => 'Le mot de passe doit être composé d\'au moins 6 caractères et contenir au moins une lettre et un chiffre.',
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Chercheur' => 'ROLE_CHERCHEUR',
                    'Etudiant' => 'ROLE_ETUDIANT',
                ],
                'expanded' => true,
                'multiple' => true, // allow only one selection
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ rôle ne peut pas être vide.',
                    ]),
                    new Count([
                        'max' => 1,
                        'maxMessage' => 'Vous ne pouvez sélectionner qu\'un seul rôle.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
