<?php

namespace App\Form;

use App\Entity\Actualite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class ActualiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('titre', null, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Le titre ne peut pas être vide',
                ]),
            ],
        ])
        ->add('description', TextareaType::class, [
            'label' => 'description',
            'constraints' => [
                new NotBlank([
                    'message' => 'La description ne peut pas être vide',
                ]),
            ],
        ])
        ->add('image', FileType::class, [
            'label' => 'Votre image de profil (Des fichiers images uniquement)',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Le champ image ne peut pas être vide',
                ]),
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/gif',
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image valide (gif, jpeg, png, jpg)',
                ]),
            ],
        ])
        
        ->add('date', null, [
            'constraints' => [
                new NotBlank([
                    'message' => 'La date ne peut pas être vide',
                ]),
            ],
        ])
    ;
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Actualite::class,
        ]);
    }
}
