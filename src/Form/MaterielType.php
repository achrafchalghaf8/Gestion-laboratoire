<?php

namespace App\Form;

use App\Entity\Materiel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;



class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('designation', null, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ designation ne peut pas être vide',
                ]),
            ],
        ])
        ->add('specifications', null, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ specifications ne peut pas être vide',
                ]),
            ],
        ])
        ->add('quantite', null, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ quantite ne peut pas être vide',
                ]),
                new Assert\GreaterThan([
                    'value' => 0,
                    'message' => 'La quantité doit être supérieure à zéro',
                ]),
            ],
        ])
        ->add('numero_inventaire', null, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ numéro inventaire ne peut pas être vide',
                ]),
               
            ],
        ])
        ->add('instruc_utilisation', TextareaType::class, [
            'label' => 'Instructions utilisation',
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ instructions utilisation ne peut pas être vide',
                ]),
            ],
        ])
        ->add('image', FileType::class, [
            'label' => 'Votre image de profil (Des fichiers images uniquement)',
            // unmapped means that this field is not associated to any entity property
            'mapped' => false,
            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,
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
                new Assert\NotBlank([
                    'message' => 'Le champ image ne peut pas être vide',
                ]),
            ],
        ])
    ;
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}


// <?php

// namespace App\Form;

// use App\Entity\Materiel;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Validator\Constraints\File;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Form\Extension\Core\Type\FileType;

// class MaterielType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options)
//     {
//         $builder
//             ->add('designation')
//             ->add('specifications')
//             ->add('quantite')
//             ->add('photo', FileType::class, [
//                 'label' => 'image materiel (fichier image uniquement)',

//                 // unmapped means that this field is not associated to any entity property
//                 'mapped' => false,

//                 // make it optional so you don't have to re-upload the PDF file
//                 // every time you edit the Product details
//                 'required' => false,

//                 // unmapped fields can't define their validation using annotations
//                 // in the associated entity, so you can use the PHP constraint classes
//                 'constraints' => [
//                     new File([
//                         'maxSize' => '1024k',
//                         'mimeTypes' => [
//                             'image/jpg',
//                             'image/jpeg',
//                             'image/png',
//                             'image/gif',
                            
//                         ],
//                         'mimeTypesMessage' => 'Please upload a valid image',
//                     ])
//                 ],
//             ])
            
//         ;
//     }

//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setDefaults([
//             'data_class' => Materiel::class,
//         ]);
//     }
// }
