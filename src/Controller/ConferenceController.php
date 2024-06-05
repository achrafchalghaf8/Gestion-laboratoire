<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Form\ConferenceType;
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
use Symfony\component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ConferenceController extends AbstractController
{
    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/gestion_conference', name: 'gestion_conference')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Conference::class);
        $conferences = $repository->findAll();
        return $this->render('conference/index.html.twig', ['conferences' => $conferences]);
    }
    #[Route('/conference', name: 'app_conference')]
    public function view (ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Conference::class);
        $conferences = $repository->findAll();
        return $this->render('conference/view.html.twig', ['conferences' => $conferences]);
    }
    
    #[Route('/conference/{id<[0-9]+>}', name: 'conference_show')]
    public function show(Conference $conference): Response
    {
        if (!$conference) {
            throw $this->createNotFoundException(
                'conference non trouvée avec l\'id' . $conference->id
            );
        }
        return $this->render('conference/show.html.twig', ['conference' => $conference]);
    }
    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/conference/create', name: 'conference_create')]
    public function create(Request $request,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        $conference = new Conference;
        $form=$this->createForm(ConferenceType::class,$conference);

        

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
                        $this->getParameter('conference_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $conference->setImage($newFilename);
            }

            $em->persist($conference);
            $em->flush();
            return $this->redirectToRoute('app_conference');
        }

            return $this->render('conference/create.html.twig',['formConference'=>$form]);
        }
        

        #[IsGranted( 'ROLE_ADMIN')]
         #[Route('/conference/{id<\d+>}/modifier', name: 'conference_edit')]
    public function modifier(Conference $conference,Request $request,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        $form=$this->createForm(ConferenceType::class,$conference);

        

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
                        $this->getParameter('conference_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $conference->setImage($newFilename);
            }

            $em->flush();
            return $this->redirectToRoute('app_conference');
        }

            return $this->render('conference/edit.html.twig',['formConference'=>$form]);
        }
        #[IsGranted( 'ROLE_ADMIN')]
        #[Route('/conference/{id<\d+>}/delete', name: 'conference_delete')]
    public function supprimer(Conference $conference,EntityManagerInterface $em)
    {
        $em->remove($conference);
        $em->flush();
        return $this->redirectToRoute('app_conference');
    }
}
