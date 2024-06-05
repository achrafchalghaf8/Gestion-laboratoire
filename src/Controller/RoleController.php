<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RoleController extends AbstractController
{
    #[Route('/role', name: 'app_role')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Role::class);
        $roles = $repository->findAll();
        return $this->render('role/index.html.twig', ['roles' => $roles]);
        
    }
    #[Route('/ajouter_role', name: 'ajouter_role')]
    public function register(Request $request,EntityManagerInterface $entityManager): Response
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          

            $entityManager->persist($role);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_role');
        }

        return $this->render('role/create.html.twig', [
            'roleForm' => $form->createView(),
        ]);
    }
    #[Route('/role/{id<\d+>}/modifier', name: 'role_edit')]
    public function modifier(Role $role,Request $request,EntityManagerInterface $em)
    {
        $form=$this->createForm(roleType::class,$role);

        

         $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            return $this->redirectToRoute('app_role');
        }

            return $this->render('role/edit.html.twig',['roleForm'=>$form]);
        }
        #[Route('/role/{id<\d+>}/delete', name: 'role_delete')]
    public function supprimer(Role $role,EntityManagerInterface $em)
    {
        $em->remove($role);
        $em->flush();
        return $this->redirectToRoute('app_role');
    }
}
