<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;







class RegistrationController extends AbstractController
{
  
    #[Route('/gestion_user', name: 'gestion_user')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(User::class);
        $users = $repository->findAll();
        return $this->render('registration/index.html.twig', ['users' => $users]);
        
    }
    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('gestion_user');
        }

        return $this->render('registration/create.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route('/user/{id<\d+>}/modifier', name: 'user_edit')]
    public function modifier(User $user,Request $request,UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $em)
    {
        $form=$this->createForm(RegistrationFormType::class,$user);

        

         $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             // encode the plain password
             $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $em->flush();
            return $this->redirectToRoute('gestion_user');
        }

            return $this->render('registration/edit.html.twig',['registrationForm'=>$form]);
        }

        #[Route('/user/{id<\d+>}/modification', name: 'modification_infos')]
        public function modify(User $user,Request $request,UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $em,TokenStorageInterface $tokenStorage)
        {
            
            $current_user = $this->getUser();
            $current_user = $tokenStorage->getToken()->getUser();
            $userId = $current_user->id;
            
            if ($user->id=== $userId){
            $form=$this->createForm(RegistrationFormType::class,$user);
    
            
    
             $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                 // encode the plain password
                 $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
    
                $em->flush();
                return $this->redirectToRoute('app_home');
            }
    
                return $this->render('registration/modify.html.twig',['registrationForm'=>$form]);
            }
            else {
                $message = 'Vous n\'êtes pas autorisé à modifier ce compte.';
return $this->render('registration/error.html.twig', ['message' => $message]);

            }}

        #[Route('/vos_infos', name: 'vos_infos')]
        public function yours(ManagerRegistry $doctrine): Response
        {
            $repository = $doctrine->getRepository(User::class);
            $users = $repository->findAll();
            return $this->render('registration/compte.html.twig', ['users' => $users]);
        }


        #[Route('/user/{id<\d+>}/delete', name: 'user_delete')]
    public function supprimer(User $user,EntityManagerInterface $em)
    {
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('gestion_user');
    }
    

}