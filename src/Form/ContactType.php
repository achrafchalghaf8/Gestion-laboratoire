<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail: doit etre sous forme "exemple@exemple.exemple"',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre adresse e-mail.'
                    ]),
                    new Email([
                        'message' => 'L\'adresse e-mail "{{ value }}" n\'est pas valide.',
                        'mode' => 'strict',
                    ]),
                ],
            ])
            ->add('sujet')
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'rows' => 6 // set the number of rows to 6
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
