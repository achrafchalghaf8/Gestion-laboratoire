<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Chercheur;
use App\Entity\Publication;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le titre ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Journal' => 'journal',
                    'Conférence' => 'conférence',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le type ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('date_publication', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'La date de publication ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('resume', TextareaType::class, [
                'label' => 'Resumé du publication',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le résumé ne doit pas être vide.',
                    ]),
                ],
            ])
            // ->add('auteur', EntityType::class, [
            //     'class' => User::class,
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er->createQueryBuilder('u')
            //             ->andWhere('u.roles LIKE :roles')
            //             ->setParameter('roles', '%ROLE_CHERCHEUR%')
            //             ->orderBy('u.nom_prenom', 'ASC');
            //     },
                
            //     'choice_label' => 'nom_prenom',
            //     'label' => 'Auteur',
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'L\'auteur ne doit pas être vide.',
            //         ]),
            //     ],
            // ])

            
            ->add('auteur', EntityType::class, [
    'class' => Chercheur::class,
    
    'choice_label' => function (Chercheur $chercheur) {
        $compte = $chercheur->getCompte();
        return $chercheur->getId() . ' - ' . ($compte ? $compte->getNomPrenom() : '');
        // Modify the return statement to include the researcher ID and the associated account name (if available)
    },
    'label' => 'Auteur',
    'constraints' => [
        new NotBlank([
            'message' => 'L\'auteur ne doit pas être vide.',
        ]),
    ],
])
            

            // ->add('auteurs', EntityType::class, [
            //     'class' => Chercheur::class,
            //     'choice_label' => 'nom_prenom',
            //     'multiple' => true,
            //     'required' => true,
            //     'expanded' => true,
            //     'label' => 'Autres auteurs',
            //     'constraints' => [
            //         new Count(['min' => 1, 'minMessage' => 'Veuillez sélectionner au moins un chercheur.']),
            //     ],
            // ])
            // ->add('others', EntityType::class, [
            //     'class' => Chercheur::class,
            //     'choice_label' => 'nom_prenom',
            //     'multiple' => true,
            //     'required' => true,
            //     'expanded' => true,
            //     'label' => 'others',
            //     'constraints' => [
            //         new Count(['min' => 1, 'minMessage' => 'Veuillez sélectionner au moins un chercheur.']),
            //     ],
            // ])
            ->add('details', null, [
                'label' => 'Détails',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Les détails ne doivent pas être vide.',
                    ]),
                ],
            ])
            ->add('autres_auteurs', null, [
                'label' => 'Autres auteurs',
                'constraints' => [
                    new NotBlank([
                        'message' => 'autres auteurs ne pas être vide.',
                    ]),
                ],
            ])
            ->add('lien_donnees', null, [
                'label' => 'Lien de données',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le lien vers les données ne doit pas être vide.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
