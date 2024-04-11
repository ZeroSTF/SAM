<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Form\NotificationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/notification')]
class NotificationController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'app_notification_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Get the currently logged-in user
        $user = $this->security->getUser();

        // Fetch notifications for the current user
        $notifications = $entityManager
            ->getRepository(Notification::class)
            ->findBy(['user' => $user], ['date' => 'DESC']);

        return $this->render('notification/index.html.twig', [
            'notifications' => $notifications
        ]);
    }

    #[Route('/new', name: 'app_notification_new', methods: ['GET', 'POST'])]
    public function new(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $notification = new Notification();
        $form = $this->createForm(NotificationType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->setEtat(false);
            $notification->setDate(new \DateTime());
            $entityManager->persist($notification);
            $entityManager->flush();

            return $this->redirectToRoute('app_notification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('notification/new.html.twig', [
            'notification' => $notification,
            'form' => $form,
        ]);
    }

    #[Route('/voir', name: 'app_notification_voir', methods: ['GET'])]
    public function voir(EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        $notifications = $entityManager
            ->getRepository(Notification::class)
            ->findBy(['user' => $user]);

        foreach ($notifications as $notification) {
            $notification->setEtat(true);
            $entityManager->persist($notification);
        }

        // Flush the changes to the database
        $entityManager->flush();

        return $this->redirectToRoute('app_notification_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_notification_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {

        $notification = $entityManager
            ->getRepository(Notification::class)
            ->find($id);

        $notification->setEtat(true);
        $entityManager->flush();

        return $this->render('notification/show.html.twig', [
            'notification' => $notification
        ]);
    }

    #[Route('/{id}/edit', name: 'app_notification_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $notification = $entityManager->getRepository(Notification::class)->find($id);
        $form = $this->createForm(NotificationType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_notification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('notification/edit.html.twig', [
            'notification' => $notification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_notification_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $notification = $entityManager
            ->getRepository(Notification::class)
            ->find($id);
        if ($this->isCsrfTokenValid('delete' . $notification->getId(), $request->request->get('_token'))) {
            $entityManager->remove($notification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_notification_index', [], Response::HTTP_SEE_OTHER);
    }
}
