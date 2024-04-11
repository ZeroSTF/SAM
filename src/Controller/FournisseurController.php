<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/fournisseur')]
class FournisseurController extends AbstractController
{
    #[Route('/', name: 'app_fournisseur_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $fournisseurs = $entityManager
            ->getRepository(Fournisseur::class)
            ->findAll();

        return $this->render('fournisseur/index.html.twig', [
            'fournisseurs' => $fournisseurs
        ]);
    }

    #[Route('/new', name: 'app_fournisseur_new', methods: ['GET', 'POST'])]
    public function new(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fournisseur);
            $entityManager->flush();

            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fournisseur/new.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fournisseur_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {

        $fournisseur = $entityManager
            ->getRepository(Fournisseur::class)
            ->find($id);

        return $this->render('fournisseur/show.html.twig', [
            'fournisseur' => $fournisseur
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fournisseur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $fournisseur = $entityManager->getRepository(Fournisseur::class)->find($id);
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fournisseur/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_fournisseur_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $fournisseur = $entityManager
            ->getRepository(Fournisseur::class)
            ->find($id);
        if ($this->isCsrfTokenValid('delete' . $fournisseur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fournisseur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
    }
}
