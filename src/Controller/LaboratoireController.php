<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Entity\Laboratoire;
use App\Form\LaboratoireType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class LaboratoireController extends AbstractController
{
    
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/gestion_labo', name: 'gestion_labo')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Laboratoire::class);
        $Laboratoires = $repository->findAll();
        return $this->render('Laboratoire/index.html.twig', ['Laboratoires' => $Laboratoires]);
        
    }
        /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    #[Route('/', name: 'app_home')]
    public function show(): Response
    {
        $entityManager = $this->managerRegistry->getManager();
        $laboratoireRepository = $entityManager->getRepository(Laboratoire::class);
        $laboratoire = $laboratoireRepository->find(1);
    
        if (!$laboratoire) {
            return $this->render('laboratoire/error_notfound.html.twig'); 
            
        }
        $actualiteRepository = $entityManager->getRepository(Actualite::class);
        $actualites = $actualiteRepository->findAll();
    
        return $this->render('accueil.html.twig', [
            'laboratoire' => $laboratoire,
            'actualites' => $actualites
        ]);
    }
    

    
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/laboratoire/create', name: 'laboratoire_create')]
    public function create(Request $request ,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        // récupérer l'objet Laboratoire qui a l'ID 1
        $laboratoire = $em->find(Laboratoire::class, 1);
    
        // vérifier si l'objet Laboratoire existe
        if ($laboratoire !== null) {
            return $this->render('laboratoire/error.html.twig');        }
    
        // créer un nouvel objet Laboratoire
        $Laboratoire = new Laboratoire;
        $form=$this->createForm(LaboratoireType::class,$Laboratoire);
    
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
                        $this->getParameter('laboratoire_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $Laboratoire->setImage($newFilename);
            }
    
            // enregistrer l'objet Laboratoire dans la base de données
            $em->persist($Laboratoire);
            $em->flush();
            
            // rediriger l'utilisateur vers une autre page
            return $this->redirectToRoute('gestion_labo');
        }
    
        // afficher le formulaire de création
        return $this->render('laboratoire/create.html.twig', [
            'formLaboratoire' => $form->createView(),
        ]);
    }
    
  
    #[IsGranted('ROLE_ADMIN')]
         #[Route('/laboratoire/{id<\d+>}/modifier', name: 'laboratoire_edit')]
    public function modifier(Laboratoire $laboratoire,Request $request,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        $form=$this->createForm(LaboratoireType::class,$laboratoire);

        

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
                        $this->getParameter('laboratoire_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $laboratoire->setImage($newFilename);
            }

            $em->flush();
            return $this->redirectToRoute('gestion_labo');
        }

            return $this->render('laboratoire/edit.html.twig',['formLaboratoire'=>$form]);
        }
}
