<?php

namespace App\Form;

use App\Entity\Equipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('historique', TextareaType::class, [
            'label' => 'historique',
            'required' => true,
        
            'constraints' => [
                new NotBlank(['message' => 'Le champ "historique" est obligatoire.']),
            ],
        ])
        ->add('objectifs', TextareaType::class, [
            'label' => 'objectifs',
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => 'Le champ "objectifs" est obligatoire.']),
            ],
        ])
        ->add('image', FileType::class, [
            'label' => 'Votre image de profil (Des fichiers images uniquement)',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/gif',
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image valide.',
                ]),
                new NotBlank(['message' => 'Le champ "image" est obligatoire.']),
            ],
        ])
    ;
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
