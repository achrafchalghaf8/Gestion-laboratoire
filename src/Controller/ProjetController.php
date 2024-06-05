<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Form\ResprojetType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class ProjetController extends AbstractController
{
    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/gestion_projet', name: 'gestion_projet')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Projet::class);
        $projets = $repository->findAll();
        return $this->render('projet/index.html.twig', ['projets' => $projets]);
    }
    #[Route('/projet', name: 'app_projet')]
    public function view(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Projet::class);
        $projets = $repository->findAll();
        return $this->render('projet/view.html.twig', ['projets' => $projets]);
    }
    #[Route('/projet/termine', name: 'projet_termine')]
    public function termine(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Projet::class);
        $projets = $repository->findAll();
        return $this->render('projet/finished.html.twig', ['projets' => $projets]);
    }
    #[Route('/projet/non_termine', name: 'projet_non_termine')]
    public function non_termine(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Projet::class);
        $projets = $repository->findAll();
        return $this->render('projet/notfinished.html.twig', ['projets' => $projets]);
    }
    #[Route('/projet/{id<[0-9]+>}', name: 'projet_show')]
    public function show(Projet $projet): Response
    {
        if (!$projet) {
            throw $this->createNotFoundException(
                'projet non trouvée avec l\'id' . $projet->id
            );
        }
        return $this->render('projet/show.html.twig', ['projet' => $projet]);
    }
    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/projet/create', name: 'projet_create')]
    public function create(Request $request,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        $projet = new Projet;
        $form=$this->createForm(ProjetType::class,$projet);

        

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
                    $this->getParameter('projet_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $projet->setImage($newFilename);
        }

            $em->persist($projet);
            $em->flush();
            return $this->redirectToRoute('app_projet');
        }

            return $this->render('projet/create.html.twig',['formProjet'=>$form]);
        }
        

        #[IsGranted( 'ROLE_ADMIN')]
         #[Route('/projet/{id<\d+>}/modifier', name: 'projet_edit')]
    public function modifier(Projet $projet,Request $request,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        $form=$this->createForm(ProjetType::class,$projet);

        

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
                    $this->getParameter('projet_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $projet->setImage($newFilename);
        }
            

            $em->flush();
            return $this->redirectToRoute('app_projet');
        }

            return $this->render('projet/edit.html.twig',['formProjet'=>$form]);
        }
        #[IsGranted( 'ROLE_ADMIN')]
       
        #[Route('/Resultat_projet/{id<\d+>}/modifier', name: 'resultat_projet')]
        public function updateProjet(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, int $id): Response
        {
            $projet = $em->getRepository(Projet::class)->find($id);
            
            if (!$projet) {
                throw $this->createNotFoundException('Le projet avec l\'id '.$id.' n\'existe pas.');
            }
        
            $form = $this->createForm(ProjetType::class, $projet);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                $photo = $form->get('image')->getData();
        
                // this condition is needed because the 'image' field is not required
                // so the file must be processed only when a file is uploaded
                if ($photo) {
                    $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
        
                    // Move the file to the directory where images are stored
                    try {
                        $photo->move(
                            $this->getParameter('projet_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
        
                    // updates the 'image' property to store the image file name
                    // instead of its contents
                    $projet->setImage($newFilename);
                }
        
                $em->flush();
        
                $this->addFlash('success', 'Le projet a été modifié avec succès.');
        
                return $this->redirectToRoute('app_projet');
            }
        
            return $this->render('projet/result.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        #[IsGranted( 'ROLE_ADMIN')]
        
        #[Route('/projet/{id<\d+>}/delete', name: 'projet_delete')]
    public function supprimer(Projet $projet,EntityManagerInterface $em)
    {
        $em->remove($projet);
        $em->flush();
        return $this->redirectToRoute('app_projet');
    }
}
