<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\User;
use App\Form\BienType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bien')]
class BienController extends AbstractController
{
    #[Route('/', name: 'app_bien_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortField = $request->query->get('sort', 'id'); // Default sorting field is 'id'

        $biens = $entityManager
            ->getRepository(Bien::class)
            ->findBy([], [$sortField => 'ASC']); // Sorting by the specified field

        return $this->render('bien/index.html.twig', [
            'biens' => $biens,
            'sortField' => $sortField, // Pass the current sort field to the template
        ]);
    }


    #[Route('/new', name: 'app_bien_new', methods: ['GET', 'POST'])]
    public function new(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $bien = new Bien();
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bien->setLat($request->request->get('lat'));
            $bien->setLon($request->request->get('lon'));
            $bien->setAdresse($request->request->get('adresse'));
            $entityManager->persist($bien);
            $entityManager->flush();

            return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bien/new.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bien_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {

        $bien = $entityManager
            ->getRepository(Bien::class)
            ->find($id);

        return $this->render('bien/show.html.twig', [
            'bien' => $bien
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bien_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {

        $bien = $entityManager->getRepository(Bien::class)->find($id);
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bien->setLat($request->request->get('lat'));
            $bien->setLon($request->request->get('lon'));
            $bien->setAdresse($request->request->get('adresse'));
            $entityManager->flush();

            return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bien/edit.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_bien_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $bien = $entityManager
            ->getRepository(Bien::class)
            ->find($id);
        if ($this->isCsrfTokenValid('delete' . $bien->getId(), $request->request->get('_token'))) {
            $entityManager->remove($bien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
    }
}
