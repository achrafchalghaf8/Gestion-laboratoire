<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;
use setasign\Fpdi\Fpdi;
use App\Entity\Chercheur;
use App\Entity\Publication;
use App\Form\PublicationType;
use App\Repository\ChercheurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PublicationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;







class PublicationController extends AbstractController
{
    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/gestion_publication', name: 'gestion_publication')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Publication::class);
        $publications = $repository->findAll();
        return $this->render('publication/index.html.twig', ['publications' => $publications]);
    }
    
    #[Route('/publication', name: 'app_publication')]
    public function view(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Publication::class);
        $publications = $repository->findAll();
        return $this->render('publication/view.html.twig', ['publications' => $publications]);
    }
    #[Route('/current_year', name: 'current_year')]
    public function current_year(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Publication::class);
        $publications = $repository->findAll();
        return $this->render('publication/current_year.html.twig', ['publications' => $publications]);
    }
    #[Route('/vos_publication', name: 'app_vos_publication')]
    public function yours(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Publication::class);
        $publications = $repository->findAll();
        return $this->render('chercheur_account/publication/view.html.twig', ['publications' => $publications]);
    }
    #[Route('/article', name: 'app_article')]
    public function article(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Publication::class);
        $publications = $repository->findAll();
        return $this->render('publication/journal.html.twig', ['publications' => $publications]);
    }
    #[Route('/publication_conference', name: 'pub_conf')]
    public function conference(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Publication::class);
        $publications = $repository->findAll();
        return $this->render('publication/conference.html.twig', ['publications' => $publications]);
    }
    #[Route('/publication/{id<[0-9]+>}', name: 'publication_show')]
    public function show(Publication $publication): Response
    {
        if (!$publication) {
            throw $this->createNotFoundException(
                'publication non trouvée avec l\'id' . $publication->id
            );
        }
        return $this->render('publication/show.html.twig', ['publication' => $publication]);
    }
    
    
    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/publication/create', name: 'publication_create')]
    public function create(Request $request,EntityManagerInterface $em)
    {
        $publication = new Publication;
        $form=$this->createForm(PublicationType::class,$publication);

        

         $form->handleRequest($request);
         
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($publication);
            $em->flush();
            return $this->redirectToRoute('app_publication');
        }

            return $this->render('publication/create.html.twig',['formPublication'=>$form]);
        }
        #[IsGranted('ROLE_CHERCHEUR')]
#[Route('/publication/create_cher', name: 'publication_chercheur_create')]
       
        public function creating(Request $request, EntityManagerInterface $em, Security $security)
        {
            $user = $security->getUser();
            $chercheur = $em->getRepository(Chercheur::class)->findOneBy(['compte' => $user]);
        
            $publication = new Publication;
            $publication->setAuteur($chercheur);
        
            $form = $this->createForm(PublicationType::class, $publication);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($publication);
                $em->flush();
        
                return $this->redirectToRoute('app_publication');
            }
        
            return $this->render('chercheur_account/publication/create.html.twig', ['formPublication' => $form->createView()]);
        }
            #[IsGranted( 'ROLE_ADMIN')]
            #[Route('/publication/{id<\d+>}/modifier', name: 'publication_edit')]
            public function modifier(Publication $publication,Request $request,EntityManagerInterface $em)
            {
                $form=$this->createForm(PublicationType::class,$publication);
            
                $form->handleRequest($request);
                
                if ($form->isSubmitted() && $form->isValid()) {
                    // $others = implode(',',$form->others);
                    // $publication->setOthers($others);
                    
                    $em->flush();
                    return $this->redirectToRoute('app_publication');
                }
                
                $errors = $form->getErrors(true, true);
                
                return $this->render('publication/edit.html.twig', [
                    'formPublication' => $form->createView(),
                    'errors' => $errors,
                ]);
            }
            
            

        #[IsGranted('ROLE_CHERCHEUR')]
        #[Route('/cher_publication/{id<\d+>}/modifier', name: 'publication_cher_edit')]
        public function modify(Publication $publication, Request $request, EntityManagerInterface $em)
        {
            $user=$this->getUser();
            if ($publication->getAuteur() == $user->nom_prenom) {
                $form = $this->createForm(PublicationType::class, $publication);
                $form->handleRequest($request);
                // dd($publication);

                if ($form->isSubmitted() && $form->isValid()) {
                    $em->flush();
                    return $this->redirectToRoute('app_publication');
                }
                
                $errors = $form->getErrors(true, false);
                
                return $this->render('chercheur_account/publication/edit.html.twig', [
                    'formPublication' => $form->createView(),
                    'errors' => $errors
                ]);
                        
            } else {
                $message = 'Vous n\'êtes pas autorisé à modifier cette publication.';
                return $this->render('chercheur_account/publication/error.html.twig', ['message' => $message]);
            }
        }
        
        
        #[Route('/publication_chercheur/{id}', name: 'publication_chercheur')]
public function show_auteur($id, PublicationRepository $repository, Security $security)
{
    $publication = $repository->find($id);

    if (!$publication) {
        throw $this->createNotFoundException('La publication n\'existe pas');
    }

    // Vérifier si l'utilisateur connecté est l'auteur de la publication
    $user = $security->getUser();
    if ($publication->getAuteur() != $user) {
        throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette publication');
    }

    return $this->render('chercheur_account/show.html.twig', [
        'publication' => $publication,
    ]);
}

        #[IsGranted( 'ROLE_ADMIN')]
        #[Route('/publication/{id<\d+>}/delete', name: 'publication_delete')]
    public function supprimer(Publication $publication,EntityManagerInterface $em)
    {
        $em->remove($publication);
        $em->flush();
        return $this->redirectToRoute('app_publication');
    }
    #[Route('/publication/{id<\d+>}/pdf', name: 'download_pdf')]
    public function downloadPdf(Request $request, Environment $twig, EntityManagerInterface $entityManager, $id)
    {
        $publication = $entityManager->getRepository(Publication::class)->find($id);
    
        if (!$publication) {
            throw $this->createNotFoundException('La publication n\'existe pas');
        }
    
        // Render the custom template with publication data
        $html = $twig->render('publication/pdf.html.twig', [
            'publication' => $publication,
        ]);
    
        // Set up Dompdf options
        $options = new Options();
        $options->set('defaultFont', 'Arial');
    
        // Instantiate Dompdf with options
        $dompdf = new Dompdf($options);
    
        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);
    
        // Render PDF document
        $dompdf->render();
    
        // Generate response with PDF contents
        $response = new Response($dompdf->output());
    
        // Set headers for PDF download
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s.pdf"', $publication->getTitre()));
    
        return $response;
    }
    
    
//     #[Route('/publication/{id<\d+>}/download', name: 'download_publication')]
//     public function downloadPublication(Request $request, $id)
// {
//     // Get the publication entity from the database
//     $entityManager = $this->getDoctrine()->getManager();
//     $publication = $entityManager->find(Publication::class, $id);

//     if (!$publication) {
//         throw $this->createNotFoundException('Publication not found.');
//     }

//     // Get the file path from the publication entity
//     $filePath = $publication->getFilePath();

//     // Check if the file exists
//     if (!file_exists($filePath)) {
//         throw $this->createNotFoundException('File not found.');
//     }

//     // Create a BinaryFileResponse object to download the file
//     $response = new BinaryFileResponse($filePath);

//     // Set the file name and content disposition headers
//     $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $publication->getFileName());
//     $response->headers->set('Content-Type', $publication->getMimeType());
//     $response->headers->set('Content-Disposition', $disposition);

//     // Send the response to the client
//     return $response;
// }
    
}
