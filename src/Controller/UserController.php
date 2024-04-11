<?php

namespace App\Controller;

use App\Entity\User;
//use App\Form\UserProfileType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();


        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/statistics', name: 'app_user_statistics', methods: ['GET'])]
    public function statistics(EntityManagerInterface $entityManager): Response
    {
        $usersByVille = $entityManager
            ->createQueryBuilder()
            ->select('u.ville, COUNT(u) as count')
            ->from(User::class, 'u')
            ->groupBy('u.ville')
            ->getQuery()
            ->getResult();

        $usersByEtat = $entityManager
            ->createQueryBuilder()
            ->select('u.etat, COUNT(u) as count')
            ->from(User::class, 'u')
            ->groupBy('u.etat')
            ->getQuery()
            ->getResult();

        $usersByDate = $entityManager
            ->createQueryBuilder()
            ->select('u.date, COUNT(u) as count')
            ->from(User::class, 'u')
            ->groupBy('u.date')
            ->getQuery()
            ->getResult();


        $data = [];
        foreach ($usersByVille as $row) {
            $data[] = [
                'label' => $row['ville'],
                'value' => $row['count'],
            ];
        }

        $data2 = [];
        foreach ($usersByEtat as $row) {
            $data2[] = [
                'label' => $row['etat'],
                'value' => $row['count'],
            ];
        }

        $data3 = [];
        foreach ($usersByDate as $row) {
            $data3[] = [
                'date' => $row['date']->format('m-d'),
                'count' => $row['count'],
            ];
        }


        return $this->render('user/statistics.html.twig', [
            'data' => $data,
            'data2' => $data2,
            'data3' => $data3,
        ]);
    }


    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPwd(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('pwd')->getData()
                )
            );
            $user->setDate(new \DateTime());
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

/*    #[Route('/{id}/profile', name: 'app_user_profile', methods: ['GET'])]
    public function profile(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager
            ->getRepository(User::class)
            ->find($id);

        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findBy(['idUser' => $id]);

        $nbProduits = count($produits);

        return $this->render('front/profile.html.twig', [
            'user' => $user,
            'produits' => $produits,
            'nbProduits' => $nbProduits,
        ]);
    }*/

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {

        $user = $entityManager
            ->getRepository(User::class)
            ->find($id);

        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(UserType::class, $user, [
            'include_password_fields' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager
            ->getRepository(User::class)
            ->find($id);
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

}
