<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Projet;
use App\Entity\Chercheur;
use App\Entity\ProjetsChercheurs;
use Doctrine\ORM\EntityRepository;
use App\Repository\ProjetRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ChercheurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('biographie', TextareaType::class, [
            'label' => 'biographie',
            'constraints' => [
                new NotBlank([
                    'message' => 'Le champ "biographie" est obligatoire.',
                ]),
            ],
        ])
        ->add('adresse', null, [
            'label' => 'Adresse',
            'required' => true,
            'attr' => [
                'placeholder' => 'Saisissez votre adresse ici'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Le champ "adresse" est obligatoire.',
                ]),
            ],
        ])
        // ->add('nom_prenom', null, [
        //     'label' => 'Nom et prénom',
        //     'required' => true,
        //     'attr' => [
        //         'placeholder' => 'Saisissez votre nom et prénom ici'
        //     ],
        //     'constraints' => [
        //         new NotBlank([
        //             'message' => 'Le champ "nom et prénom" est obligatoire.',
        //         ]),
        //     ],
        // ])
        ->add('compte', EntityType::class, [
            'class' => User::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->andWhere('u.roles LIKE :roles')
                    ->setParameter('roles', '%ROLE_CHERCHEUR%')
                    ->orderBy('u.nom_prenom', 'ASC');
            },
            'choice_label' => 'nom_prenom',
            'label' => 'Nom et prénom',
            'constraints' => [
                new NotBlank([
                    'message' => 'Le champ "compte" est obligatoire.',
                ]),
            ],
        ])
        ->add('profession', null, [
            'label' => 'Profession',
            'required' => true,
            'attr' => [
                'placeholder' => 'Saisissez votre profession ici'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Le champ "profession" est obligatoire.',
                ]),
            ],
        ])
        ->add('image', FileType::class, [
            'label' => 'Votre image de profil (Des fichiers images uniquement)',
            'mapped' => false,
            'required' => true, // change this to true
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/gif',
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                    ],
                    'mimeTypesMessage' => 'Importez un type image valide(gif,jpeg,png,jpg)',
                ]),
                new NotBlank([
                    'message' => 'Veuillez sélectionner une image',
                ]),
            ],
        ])
        ;
}
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chercheur::class,
        ]);
    }
}
