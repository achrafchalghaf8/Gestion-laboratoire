<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class ReservationController extends AbstractController
{
    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/gestion_reservation', name: 'gestion_reservation')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Reservation::class);
        $reservations = $repository->findAll();
        return $this->render('reservation/index.html.twig', ['reservations' => $reservations]);
    }
    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/current_year_reservations', name: 'current_year_reservations')]
    public function current_year_reservations(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Reservation::class);
        $reservations = $repository->findAll();
        return $this->render('reservation/current_year_reservations.html.twig', ['reservations' => $reservations]);
    }
    #[IsGranted( 'ROLE_CHERCHEUR')]
    #[Route('/vos_reservations', name: 'vos_reservations')]
    public function yours(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Reservation::class);
        $reservations = $repository->findAll();
        return $this->render('chercheur_account/reservation/view.html.twig', ['reservations' => $reservations]);
    }
    #[IsGranted( 'ROLE_USER')]
    #[Route('/reservations', name: 'reservations')]
    public function reservations(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Reservation::class);
        $reservations = $repository->findAll();
        return $this->render('reservation/view.html.twig', ['reservations' => $reservations]);
    }
    #[IsGranted( 'ROLE_USER')]
    #[Route('/vos_reservations', name: 'vos_reservations')]
    public function vos_reservations(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Reservation::class);
        $reservations = $repository->findAll();
        return $this->render('reservation/yours.html.twig', ['reservations' => $reservations]);
    }

    #[IsGranted( 'ROLE_ETUDIANT')]
    #[Route('/mes_reservations', name: 'mes_reservations')]
    public function vos(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Reservation::class);
        $reservations = $repository->findAll();
        return $this->render('etudiant/reservation/view.html.twig', ['reservations' => $reservations]);
    }
    #[IsGranted( 'ROLE_USER')]
    #[Route('/reservation/{id<[0-9]+>}', name: 'reservation_show')]
    public function show(Reservation $reservation): Response
    {
        if (!$reservation) {
            throw $this->createNotFoundException(
                'reservation non trouvée avec l\'id' . $reservation->id
            );
        }
        return $this->render('reservation/show.html.twig', ['reservation' => $reservation]);
    }
    
    
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/reservation/create', name: 'reservation_create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $materiel = $reservation->getMateriel();
            $dateDebut = $reservation->getDateDebut();
            $dateFin=$reservation->getDateFin();
            $reservations = $em->getRepository(Reservation::class)->findByMaterielAndDate(
                $materiel,
                $reservation->getDateDebut(),
                $reservation->getDateFin()
            );
            if (count($reservations) >= $materiel->getQuantite()) {
                // Le matériel est déjà réservé pour cette période, afficher un message d'erreur
                $this->addFlash('warning', 'Le matériel est déjà réservé pour cette période');
    
            } else {
         
    
            $em->persist($reservation);
            $em->flush();
            $this->addFlash('success', 'La réservation a été ajoutée avec succès.');
            return $this->redirectToRoute('app_materiel');
        } }
    
        return $this->render('reservation/create.html.twig', ['formReservation' => $form->createView()]);
    }
    
    
    #[IsGranted('ROLE_USER')]
    #[Route('/votre_reservation/{id<\d+>}/modifier', name: 'modifier_votre_reservation')]
    public function modify(Reservation $reservation, Request $request, EntityManagerInterface $em)
    {
        $user=$this->getUser();
        if ($reservation->getDemandeur() == $user->nom_prenom) {
            $form = $this->createForm(ReservationType::class, $reservation);
    
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->flush();
                return $this->redirectToRoute('vos_reservations');
            }
    
            return $this->render('chercheur_account/reservation/edit.html.twig', ['formReservation' => $form->createView()]);
        } else {
            throw new AccessDeniedHttpException('Vous n\'êtes pas autorisé à modifier cette reservation.');
        }
    }

        #[Route('/reservation/{id<\d+>}/modifier', name: 'reservation_edit')]
    public function modifier(Reservation $reservation,Request $request,EntityManagerInterface $em)
    {
        $form=$this->createForm(ReservationType::class,$reservation);

        

         $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            return $this->redirectToRoute('app_materiel');
        }

            return $this->render('reservation/edit.html.twig',['formReservation'=>$form]);
        }
        
        #[Route('/reservation/{id<\d+>}/delete', name: 'reservation_delete')]
    public function supprimer(Reservation $reservation,EntityManagerInterface $em)
    {
        $em->remove($reservation);
        $em->flush();
        return $this->redirectToRoute('gestion_reservation');
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/votre_reservation/{id<\d+>}/annuler', name: 'annuler_votre_reservation')]
    public function annuler(Reservation $reservation, EntityManagerInterface $em)
    {
        $user=$this->getUser();
        if ($reservation->getDemandeur() == $user->nom_prenom) {
            $em->remove($reservation);
            $em->flush();
            return $this->redirectToRoute('vos_reservations');
        } else {
            throw new AccessDeniedHttpException('Vous n\'êtes pas autorisé à modifier cette reservation.');
        }
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/valider_reservation/{id<\d+>}', name: 'valider_reservation')]
    public function validerReservation(Request $request, EntityManagerInterface $entityManager, Reservation $reservation, MailerInterface $mailer): Response
    {
        
        $reservation->setValide(true);
        $entityManager->persist($reservation);
        $entityManager->flush();
    

        $email = (new Email())
        ->from('chalghaf.achraf@gmail.com')
        ->to($reservation->getDemandeur()->getEmail())
        ->subject('Confirmation de réservation')
        ->html(
            $this->renderView(
                'emails/confirmation_reservation.html.twig',
                ['reservation' => $reservation]
            )
        );
    
    $mailer->send($email);
    
    
        $this->addFlash('success', 'Reservation validée avec succès');
    
        return $this->redirectToRoute('gestion_reservation');
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/refuser_reservation/{id<\d+>}', name: 'refuser_reservation')]
    public function RefuserReservation(Request $request, EntityManagerInterface $entityManager, Reservation $reservation, MailerInterface $mailer): Response
    {
        $reservation->setValide(false);
        $raisonRefus = $request->request->get('raison_refus');
        if ($raisonRefus !== null) {
            $email = (new Email())
                ->from('chalghaf.achraf@gmail.com')
                ->to($reservation->getDemandeur()->getEmail())
                ->subject('Refus de réservation')
                ->html(
                    $this->renderView(
                        'emails/refus_reservation.html.twig',
                        ['reservation' => $reservation, 'raisonRefus' => $raisonRefus]
                    )
                );
            $mailer->send($email);
        }
        
        $entityManager->persist($reservation);
        $entityManager->flush();
        return $this->redirectToRoute('gestion_reservation');
    }
    
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/retour_reservation/{id<\d+>}', name: 'retour_reservation')]
    public function retournerReservation(Request $request, EntityManagerInterface $entityManager, Reservation $reservation): Response
    {
        $retourne = $request->request->get('retourne'); // récupérer la valeur du champ "retourne" envoyé via POST
        if ($retourne) {
            $reservation->setRetourne(true); // si le champ est coché, mettre la valeur de retourne à true
        } else {
            $reservation->setRetourne(false); // sinon, mettre la valeur de retourne à false
        }
        $entityManager->persist($reservation);
        $entityManager->flush();
        return $this->redirectToRoute('gestion_reservation');
    }
    
    
    
}
