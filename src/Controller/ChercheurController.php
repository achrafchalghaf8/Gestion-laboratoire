<?php

namespace App\Controller;

use App\Entity\Chercheur;
use App\Form\ChercheurType;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted as ConfigurationIsGranted;


class ChercheurController extends AbstractController
{
    #[IsGranted( 'ROLE_ADMIN')]
    
    #[Route('/gestion_chercheurs', name: 'gestion_chercheurs')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Chercheur::class);
        $chercheurs = $repository->findBy(['est_supprime' => false]);
        return $this->render('chercheur/index.html.twig', ['chercheurs' => $chercheurs]);
    }
    #[Route('/chercheurs', name: 'app_chercheur')]
    public function vue(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Chercheur::class);
        $chercheurs = $repository->findBy(['est_supprime' => false]);
        return $this->render('chercheur/view.html.twig', ['chercheurs' => $chercheurs]);
        
    }
    #[Route('/chercheur/{id<[0-9]+>}', name: 'chercheur_show')]
    public function show(Chercheur $chercheur, ProjetRepository $projetRepository): Response
    {
        if (!$chercheur) {
            throw $this->createNotFoundException(
                'chercheur non trouvée avec l\'id' . $chercheur->id
            );
        }
    
        // Retrieve all the projects
        $projets = $projetRepository->findAll();
    
        return $this->render('chercheur/show.html.twig', [
            'chercheur' => $chercheur,
            'projets' => $projets
        ]);
    }
    
    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/chercheur/create', name: 'chercheur_create')]
    public function create(Request $request,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        $chercheur = new Chercheur;
        $form=$this->createForm(ChercheurType::class,$chercheur);

        

         $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
    
                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('chercheur_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $chercheur->setImage($newFilename);
            }

            $em->persist($chercheur);
            $em->flush();
            return $this->redirectToRoute('app_chercheur');
        }

            return $this->render('chercheur/create.html.twig',['formChercheur'=>$form]);
        }
        #[IsGranted( 'ROLE_ADMIN')]
         #[Route('/chercheur/{id<\d+>}/modifier', name: 'chercheur_edit')]
    public function modifier(Chercheur $chercheur,Request $request,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        $form=$this->createForm(ChercheurType::class,$chercheur);

        

         $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
    
                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('chercheur_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $chercheur->setImage($newFilename);
            }

            $em->flush();
            return $this->redirectToRoute('app_chercheur');
        }

            return $this->render('chercheur/edit.html.twig',['formChercheur'=>$form]);
        }
        #[IsGranted( 'ROLE_ADMIN')]
        #[Route('/chercheur/{id<\d+>}/delete', name: 'chercheur_delete')]
        public function supprimer(Chercheur $chercheur, EntityManagerInterface $em)
        {
            $chercheur->setEstSupprime(true); // Définir la propriété à true
            $em->flush(); // Enregistrer les modifications dans la base de données
            return $this->redirectToRoute('app_chercheur');
        }
        #[Route('/votre_profil', name: 'votre_profil')]
        public function yours(ManagerRegistry $doctrine): Response
        {
            $repository = $doctrine->getRepository(Chercheur::class);
            $chercheurs = $repository->findAll();
            return $this->render('chercheur_account/chercheur/view.html.twig', ['chercheurs' => $chercheurs]);
        }
        #[IsGranted('ROLE_CHERCHEUR')]
        #[Route('/cher_profil/{id<\d+>}/modifier', name: 'profil_edit')]
        public function modify(Chercheur $chercheur, Request $request, EntityManagerInterface $em,SluggerInterface $slugger,TokenStorageInterface $tokenStorage)
        {
            $user = $this->getUser();
            $user = $tokenStorage->getToken()->getUser();
            $userId = $user->id;
            
            if ($chercheur->getCompte()->getId() === $userId)
            {
               
        $form=$this->createForm(ChercheurType::class,$chercheur);

        

        $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
           $photo = $form->get('image')->getData();

           // this condition is needed because the 'brochure' field is not required
           // so the PDF file must be processed only when a file is uploaded
           if ($photo) {
               $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
               // this is needed to safely include the file name as part of the URL
               $safeFilename = $slugger->slug($originalFilename);
               $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
   
               // Move the file to the directory where brochures are stored
               try {
                   $photo->move(
                       $this->getParameter('chercheur_directory'),
                       $newFilename
                   );
               } catch (FileException $e) {
                   // ... handle exception if something happens during file upload
               }
   
               // updates the 'brochureFilename' property to store the PDF file name
               // instead of its contents
               $chercheur->setImage($newFilename);
           }

           $em->flush();
           return $this->redirectToRoute('app_chercheur');
       }
        
                return $this->render('chercheur_account/chercheur/edit.html.twig', ['formChercheur' => $form->createView()]);
            } else {
                $message = 'Vous n\'êtes pas autorisé à modifier ce profil.';
                 return $this->render('chercheur_account/chercheur/error.html.twig', ['message' => $message]);

            }
        }
        
}
