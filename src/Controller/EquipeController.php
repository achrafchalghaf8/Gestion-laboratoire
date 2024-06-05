<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class EquipeController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/gestion_infos', name: 'gestion_infos')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Equipe::class);
        $equipes = $repository->findAll();
        return $this->render('equipe/index.html.twig', ['equipes' => $equipes]);
        
    }
        /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    // #[Route('/infos', name: 'infos')]
    // public function show(): Response
    // {
    //     $entityManager = $this->managerRegistry->getManager();
    //     $EquipeRepository = $entityManager->getRepository(Equipe::class);
    //     $equipeRepository = $EquipeRepository->find(1);
    
    //     if (!$equipeRepository) {
    //         throw $this->createNotFoundException(
    //             'infos non trouvé avec l\'id 1'
    //         );
    //     }
       
    
    //     return $this->render('equipe/view.html.twig', [
    //         'equipe' => $equipe,
            
    //     ]);
    // }
    
    #[Route('infos', name: 'infos')]
    public function show(): Response
    {

        $entityManager = $this->managerRegistry->getManager();
         $EquipeRepository = $entityManager->getRepository(Equipe::class);  
        $equipe = $EquipeRepository->find(1);
    
        if (!$equipe) {
            return $this->render('equipe/error_notfound.html.twig'); 

        }

        
    
        return $this->render('equipe/show.html.twig', ['equipe' => $equipe]);
    }
    

    
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/infos_Equipe/create', name: 'infos_Equipe_create')]
    public function create(Request $request ,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        $equipe = $em->find(Equipe::class, 1);
    
        // vérifier si l'objet existe
        if ($equipe !== null) {
            return $this->render('equipe/error.html.twig');        }
        $Equipe = new Equipe;
        $form=$this->createForm(EquipeType::class,$Equipe);

        

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
                    $this->getParameter('equipe_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $Equipe->setImage($newFilename);
        }

            $em->persist($Equipe);
            try {
                $em->flush();
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                // gestion de l'exception
                $message = "L'ID " . $Equipe->getId() . " existe déjà dans la base de données.";
                trigger_error($message, E_USER_WARNING);
            }
            
            // $em->flush();
            return $this->redirectToRoute('gestion_infos');
        }

            return $this->render('equipe/create.html.twig',['formEquipe'=>$form]);

        }
  
    #[IsGranted('ROLE_ADMIN')]
         #[Route('/Equipe/{id<\d+>}/modifier', name: 'equipe_infos_edit')]
    public function modifier(Equipe $equipe,Request $request,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        $form=$this->createForm(EquipeType::class,$equipe);

        

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
                        $this->getParameter('Equipe_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $equipe->setImage($newFilename);
            }

            $em->flush();
            return $this->redirectToRoute('gestion_infos');
        }

            return $this->render('equipe/edit.html.twig',['formEquipe'=>$form]);
        }
}
