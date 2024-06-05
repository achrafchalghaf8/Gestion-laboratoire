<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\Chercheur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre', null, [
            'label' => 'Titre du projet',
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir le titre du projet',
                ]),
            ],
        ])
        ->add('description', TextareaType::class, [
            'required' => true,
            'label' => 'Description du projet',
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir la description du projet',
                ]),
            ],
        ])
        ->add('date_debut')
        ->add('date_fin')
        ->add('description_resultat', TextareaType::class, [
            'label' => 'Description resultats du projet',
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir la description des résultats du projet',
                ]),
            ],
        ])
        ->add('image', FileType::class, [
            'label' => 'Votre image de profil (Des fichiers images uniquement)',
            'mapped' => false,
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez télécharger une image de profil',
                ]),
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/gif',
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image valide',
                ]),
            ],
        ])
        ->add('chercheurs', EntityType::class, [
            'class' => Chercheur::class,
            'choice_label' => 'compte',
            'multiple' => true,
            'required' => true,
            'expanded'=> true,
            'constraints' => [
                new Count(['min' => 1, 'minMessage' => 'Veuillez sélectionner au moins un chercheur.']),

                
            ],
        ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}