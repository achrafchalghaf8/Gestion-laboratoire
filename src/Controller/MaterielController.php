<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class MaterielController extends AbstractController
{    #[IsGranted('ROLE_ADMIN')]
    #[Route('/gestion_materiel', name: 'gestion_materiel')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Materiel::class);
        $materiels = $repository->findAll();
        return $this->render('materiel/index.html.twig', ['materiels' => $materiels]);
        
    }
    #[Route('/materiels', name: 'app_materiel')]
    public function vue(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Materiel::class);
        $materiels = $repository->findAll();
        return $this->render('materiel/view.html.twig', ['materiels' => $materiels]);
        
    }
    #[Route('/materiel/{id<[0-9]+>}', name: 'materiel_show')]
    public function show(Materiel $materiel): Response
    {
        
        if (!$materiel) {
            throw $this->createNotFoundException(
                'materiel non trouvée avec l\'id' . $materiel->id
            );
        }
        return $this->render('materiel/show.html.twig', ['materiel' => $materiel]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/materiel/create', name: 'materiel_create')]
    public function create(Request $request ,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        $materiel = new Materiel;
        $form=$this->createForm(MaterielType::class,$materiel);

        

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
                    $this->getParameter('materiel_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $materiel->setImage($newFilename);
        }

            $em->persist($materiel);
            $em->flush();
            return $this->redirectToRoute('app_materiel');
        }

            return $this->render('materiel/create.html.twig',['formMateriel'=>$form]);

        }
  
    #[IsGranted('ROLE_ADMIN')]
         #[Route('/materiel/{id<\d+>}/modifier', name: 'materiel_edit')]
    public function modifier(Materiel $materiel,Request $request,EntityManagerInterface $em,SluggerInterface $slugger)
    {
        $form=$this->createForm(MaterielType::class,$materiel);

        

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
                        $this->getParameter('materiel_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $materiel->setImage($newFilename);
            }

            $em->flush();
            return $this->redirectToRoute('app_materiel');
        }

            return $this->render('materiel/edit.html.twig',['formMateriel'=>$form]);
        }
        #[IsGranted('ROLE_ADMIN')]
        #[Route('/materiel/{id<\d+>}/delete', name: 'materiel_delete')]
    public function supprimer(Materiel $materiel,EntityManagerInterface $em)
    {
        $em->remove($materiel);
        $em->flush();
        return $this->redirectToRoute('app_materiel');
    }
    #[IsGranted('ROLE_USER')]
#[Route('/materiel/{id<\d+>}/reserver', name: 'materiel_reservation')]
public function reserver(Request $request, Materiel $materiel, EntityManagerInterface $entityManager)
{
    $user = $this->getUser();

    // Créer une nouvelle instance de Reservation
    $reservation = new Reservation();
    $reservation->setMateriel($materiel);
    $reservation->setDemandeur($user);

    // Créer un formulaire pour saisir les détails de la réservation
    $form = $this->createForm(ReservationType::class, $reservation);

    // Traitement de la demande POST
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

        // Vérifier si le matériel est déjà réservé pour cette période
        $reservations = $entityManager->getRepository(Reservation::class)->findByMaterielAndDate(
            $materiel,
            $reservation->getDateDebut(),
            $reservation->getDateFin()
        );

        // Vérifier si le nombre de réservations pour cette période est égal à la quantité de matériel
        if (count($reservations) >= $materiel->getQuantite()) {
            // Le matériel est déjà réservé pour cette période, afficher un message d'erreur
            $this->addFlash('warning', 'Le matériel est déjà réservé pour cette période');

        } else {
            // Enregistrer la réservation dans la base de données
            $entityManager->persist($reservation);
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page de confirmation
            return $this->redirectToRoute('app_materiel');
        }
    }

    // Afficher le formulaire pour la saisie des détails de la réservation
    return $this->render('reservation/create.html.twig', [
        'formReservation' => $form->createView(),
        'materiel' => $materiel,
    ]);
}
#[IsGranted('ROLE_ADMIN')]
#[Route('/materiel/{id<\d+>}/reserver', name: 'materiel_reserver')]
public function reservation(Request $request, Materiel $materiel, EntityManagerInterface $entityManager)
{

    // Créer une nouvelle instance de Reservation
    $reservation = new Reservation();
    $reservation->setMateriel($materiel);
   

    // Créer un formulaire pour saisir les détails de la réservation
    $form = $this->createForm(ReservationType::class, $reservation);

    // Traitement de la demande POST
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

        // Vérifier si le matériel est déjà réservé pour cette période
        $reservations = $entityManager->getRepository(Reservation::class)->findByMaterielAndDate(
            $materiel,
            $reservation->getDateDebut(),
            $reservation->getDateFin()
        );

        // Vérifier si le nombre de réservations pour cette période est égal à la quantité de matériel
        if (count($reservations) >= $materiel->getQuantite()) {
            // Le matériel est déjà réservé pour cette période, afficher un message d'erreur
            $this->addFlash('warning', 'Le matériel est déjà réservé pour cette période');

        } else {
            // Enregistrer la réservation dans la base de données
            $entityManager->persist($reservation);
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page de confirmation
            return $this->redirectToRoute('app_materiel');
        }
    }

    // Afficher le formulaire pour la saisie des détails de la réservation
    return $this->render('reservation/create.html.twig', [
        'formReservation' => $form->createView(),
        'materiel' => $materiel,
    ]);
}

// #[IsGranted('ROLE_USER')]
// #[Route('/materiel/{id<\d+>}/reserver', name: 'materiel_reservation')]
// public function reserver(Request $request, Materiel $materiel, EntityManagerInterface $entityManager)
// {
//     $user = $this->getUser();

//     // Créer une nouvelle instance de Reservation
//     $reservation = new Reservation();
//     $reservation->setMateriel($materiel);
//     $reservation->setDemandeur($user);

//     // Créer un formulaire pour saisir les détails de la réservation
//     $form = $this->createForm(ReservationType::class, $reservation);

//     // Traitement de la demande POST
//     $form->handleRequest($request);
//     if ($form->isSubmitted() && $form->isValid()) {
//         $anciens = $entityManager->getRepository(Reservation::class)->findByMaterielNonRetourne(
//             $materiel,
//             $reservation->getDateDebut()
//         );
      

//         // Vérifier si le matériel est déjà réservé pour cette période
//         $reservations = $entityManager->getRepository(Reservation::class)->findByMaterielAndDate(
//             $materiel,
//             $reservation->getDateDebut(),
//             $reservation->getDateFin()
//         );
//         $anciens_res=count($anciens);
//         $current=count($reservations);

//         // Vérifier si le nombre de réservations pour cette période est égal à la quantité de matériel
//         if (($anciens_res+ $current) >= $materiel->getQuantite() ){
//             // Le matériel est déjà réservé pour cette période, afficher un message d'erreur
//             $this->addFlash('warning', 'Le matériel est déjà réservé pour cette période');

//         } else {
//             // Enregistrer la réservation dans la base de données
//             $entityManager->persist($reservation);
//             $entityManager->flush();

//             // Rediriger l'utilisateur vers la page de confirmation
//             return $this->redirectToRoute('app_materiel');
//         }
//     }

//     // Afficher le formulaire pour la saisie des détails de la réservation
//     return $this->render('reservation/create.html.twig', [
//         'formReservation' => $form->createView(),
//         'materiel' => $materiel,
//     ]);
// }


    }





