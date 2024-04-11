<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Bien;
use App\Entity\User;
use App\Form\ActionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{idb}/action')]
class ActionController extends AbstractController
{
    #[Route('/new', name: 'app_action_new', methods: ['GET', 'POST'])]
    public function new(int $idb, Request $request,  EntityManagerInterface $entityManager): Response
    {
        $action = new Action();
        $form = $this->createForm(ActionType::class, $action);
        $form->handleRequest($request);
        $bien=$entityManager
            ->getRepository(Bien::class)
            ->find($idb);

        if ($form->isSubmitted() && $form->isValid()) {
            $action->setBien($bien);
            $entityManager->persist($action);
            $entityManager->flush();

            return $this->redirectToRoute('app_action_index', ['idb' => $idb], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('action/new.html.twig', [
            'action' => $action,
            'form' => $form,
            'idb' => $idb
        ]);
    }
    #[Route('/', name: 'app_action_index', methods: ['GET'])]
    public function index(int $idb, EntityManagerInterface $entityManager): Response
    {
        $bien=$entityManager
            ->getRepository(Bien::class)
            ->find($idb);
        $actions = $entityManager
            ->getRepository(Action::class)
            ->findBy(['bien' => $bien]);

        return $this->render('action/index.html.twig', [
            'actions' => $actions,
            'idb' =>$idb
        ]);
    }

    #[Route('/{id}', name: 'app_action_show', methods: ['GET'])]
    public function show(int $idb, int $id, EntityManagerInterface $entityManager): Response
    {

        $action = $entityManager
            ->getRepository(Action::class)
            ->find($id);

        return $this->render('action/show.html.twig', [
            'action' => $action,
            'idb'=> $idb
        ]);
    }

    #[Route('/{id}/edit', name: 'app_action_edit', methods: ['GET', 'POST'])]
    public function edit(int $idb, Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $action = $entityManager->getRepository(Action::class)->find($id);
        $form = $this->createForm(ActionType::class, $action);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_action_index', ['idb'=>$idb], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('action/edit.html.twig', [
            'action' => $action,
            'form' => $form,
            'idb' =>$idb
        ]);
    }

    #[Route('/{id}/delete', name: 'app_action_delete', methods: ['POST'])]
    public function delete(int $idb, Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $action = $entityManager
            ->getRepository(Action::class)
            ->find($id);
        if ($this->isCsrfTokenValid('delete' . $action->getId(), $request->request->get('_token'))) {
            $entityManager->remove($action);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_action_index', ['idb'=>$idb], Response::HTTP_SEE_OTHER);
    }
}
