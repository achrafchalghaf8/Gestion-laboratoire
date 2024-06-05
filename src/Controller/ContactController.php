<?php

namespace App\Controller;

use DateTime;
use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $contact->setDateContact(new DateTime());
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           

            $entityManager->persist($contact);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
   

    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/liste_contact', name: 'liste_contact')]
    public function view (ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Contact::class);
        $contacts = $repository->findAll();
        return $this->render('contact/index.html.twig', ['contacts' => $contacts]);
    }
    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/contact_detail/{id<[0-9]+>}', name: 'contact_detail')]
    public function show(Contact $contact): Response
    {
        if (!$contact) {
            throw $this->createNotFoundException(
                'contact non trouvée avec l\'id' . $contact->id
            );
        }
        return $this->render('contact/show.html.twig', ['contact' => $contact]);
    }
    #[IsGranted( 'ROLE_ADMIN')]
    #[Route('/contact/{id<\d+>}/delete', name: 'contact_delete')]
    public function supprimer(Contact $contact,EntityManagerInterface $em)
    {
        $em->remove($contact);
        $em->flush();
        return $this->redirectToRoute('liste_contact');
    }
}
