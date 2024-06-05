<?php

namespace App\Form;

use App\Entity\Conference;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ConferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_conference', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom de la conférence est obligatoire',
                    ]),
                ],
                'required' => true,
            ])
            ->add('date_debut', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'La date de début est obligatoire',
                    ]),
                ],
                'required' => true,
            ])
            ->add('dure', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'La durée est obligatoire',
                    ]),
                ],
                'required' => true,
            ])
            ->add('lieu', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le lieu est obligatoire',
                    ]),
                ],
                'required' => true,
            ])
            ->add('domaine', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le domaine est obligatoire',
                    ]),
                ],
                'required' => true,
            ])
            ->add('sponseurs', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Les sponsors sont obligatoires',
                    ]),
                ],
                'required' => true,
            ])
            ->add('nb_participants', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nombre de participants est obligatoire',
                    ]),
                    new Positive([
                        'message' => 'Le nombre de participants doit être supérieur à zéro',
                    ]),
                ],
                'required' => true,
            ])
            
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description est obligatoire',
                    ]),
                ],
                'required' => true,
            ])
            ->add('image', FileType::class, [
                'label' => 'Votre image de profil (Des fichiers images uniquement)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it required so user must upload an image
                'required' => true,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
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
                    new NotBlank([
                        'message' => 'L\'image est obligatoire',
                    ]),
                ],
            ])
;
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conference::class,
        ]);
    }
}
