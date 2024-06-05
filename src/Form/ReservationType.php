<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Materiel;
use App\Entity\Reservation;
use Doctrine\ORM\EntityRepository;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_debut')
            ->add('date_fin')
            // ->add('num_tel', null, [
            //     'label' => 'Numéro de téléphone: doit etre formé de 8 chiffres exactement'
            // ])
            ->add('demandeur', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.roles NOT LIKE :roles')
                        ->setParameter('roles','%ROLE_ADMIN%')
                        ->orderBy('u.nom_prenom', 'ASC');
                }
                ,
                'choice_label' => 'nom_prenom',
                'label' => 'Demandeur',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le demandeur ne doit pas être vide.',
                    ]),
                ],
            ])
            ->add('materiel', EntityType::class, [
                'class' => Materiel::class,
                'choice_label' => 'designation'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
