<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Form\ActualiteType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class ActualiteController extends AbstractController
{
    #[IsGranted( 'ROLE_ADMIN')]
    
    #[Route('/gestion_actualites', name: 'gestion_actualites')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Actualite::class);
        $actualites = $repository->findAll();
        return $this->render('actualite/index.html.twig', ['actualites' => $actualites]);
    }
    // #[Route('/', name: 'app_home')]
    // public function vue(ManagerRegistry $doctrine): Response
    // {
    //     $repository = $doctrine->getRepository(Actualite::class);
    //     $actualites = $repository->findAll();
    //     return $this->render('accueil.html.twig', ['actualites' => $actualites]);
        
    // }
    #[Route('/actualite/{id<[0-9]+>}', name: 'actualite_show')]
    public function show(Actualite $actualite): Response
    {
        if (!$actualite) {
            throw $this->createNotFoundException(
                'actualite non trouvée avec l\'id' . $actualite->id
            );
        }
        return $this->render('actualite/show.html.twig', ['actualite' => $actualite]);
    }
    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/actualite/create', name: 'actualite_create')]
    public function create(Request $request,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        $actualite = new Actualite;
        $form=$this->createForm(ActualiteType::class,$actualite);

        

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
                        $this->getParameter('actualite_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $actualite->setImage($newFilename);
            }

            $em->persist($actualite);
            $em->flush();
            return $this->redirectToRoute('gestion_actualites');
        }

            return $this->render('actualite/create.html.twig',['formActualite'=>$form]);
        }
        #[IsGranted( 'ROLE_ADMIN')]
         #[Route('/actualite/{id<\d+>}/modifier', name: 'actualite_edit')]
    public function modifier(Actualite $actualite,Request $request,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        $form=$this->createForm(ActualiteType::class,$actualite);

        

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
                        $this->getParameter('actualite_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $actualite->setImage($newFilename);
            }

            $em->flush();
            return $this->redirectToRoute('gestion_actualites');
        }

            return $this->render('actualite/edit.html.twig',['formActualite'=>$form]);
        }
        #[IsGranted( 'ROLE_ADMIN')]
        #[Route('/actualite/{id<\d+>}/delete', name: 'actualite_delete')]
        public function supprimer(Actualite $actualite, EntityManagerInterface $em)
        {
            $em->remove($actualite);
        $em->flush();
        return $this->redirectToRoute('gestion_actualites');
        }
        
}
