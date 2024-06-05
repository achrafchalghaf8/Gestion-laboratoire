<?php

namespace App\Form;

use App\Entity\Laboratoire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class LaboratoireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('definition', TextareaType::class, [
                'label' => 'Definition',
                'constraints' => [
                    new NotBlank([
                        'message' => '   •  Veuillez saisir une définition',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Votre image de profil (Des fichiers images uniquement)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez télécharger une image',
                    ]),
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Laboratoire::class,
        ]);
    }
}
