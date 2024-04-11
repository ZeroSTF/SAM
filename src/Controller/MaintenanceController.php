<?php

namespace App\Controller;

use App\Entity\Maintenance;
use App\Form\MaintenanceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/maintenance')]
class MaintenanceController extends AbstractController
{
    #[Route('/', name: 'app_maintenance_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $maintenances = $entityManager
            ->getRepository(Maintenance::class)
            ->findBy([], ['date' => 'DESC']);

        return $this->render('maintenance/index.html.twig', [
            'maintenances' => $maintenances
        ]);
    }

    #[Route('/new', name: 'app_maintenance_new', methods: ['GET', 'POST'])]
    public function new(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $maintenance = new Maintenance();
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($maintenance);
            $entityManager->flush();

            return $this->redirectToRoute('app_maintenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('maintenance/new.html.twig', [
            'maintenance' => $maintenance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_maintenance_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $maintenance = $entityManager
            ->getRepository(Maintenance::class)
            ->find($id);

        return $this->render('maintenance/show.html.twig', [
            'maintenance' => $maintenance
        ]);
    }

    #[Route('/{id}/edit', name: 'app_maintenance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $maintenance = $entityManager->getRepository(Maintenance::class)->find($id);
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submittedData = $form->getData();
            dump($submittedData);
            $entityManager->flush();
            return $this->redirectToRoute('app_maintenance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('maintenance/edit.html.twig', [
            'maintenance' => $maintenance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_maintenance_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $maintenance = $entityManager
            ->getRepository(Maintenance::class)
            ->find($id);
        if ($this->isCsrfTokenValid('delete' . $maintenance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($maintenance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_maintenance_index', [], Response::HTTP_SEE_OTHER);
    }
}
