<?php

namespace App\Controller\Web;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractController
{
    private $entityManager;
    private $userRepository;
    private $groupRepository;

    public function __construct (
        EntityManagerInterface $entityManager, 
        UserRepository $userRepository,
        GroupRepository $groupRepository
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
    } 
        
    public function index(): Response
    {
        return $this->render('users/index.html.twig', [
            'users' => $this->entityManager->getRepository(User::class)->findAll(),
        ]);
    }

    public function create(Request $request): Response
    {
        $form = $this->createForm(UserFormType::class, new User());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();
            $newUser = $form->getData();

            $newUser->setName($name);
            $this->userRepository->save($newUser, true);

            return $this->redirectToRoute('app_users_index');
        }

        return $this->render('users/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit($id, Request $request): Response
    {
        $user = $this->userRepository->find($id);
        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();
            $updatedUser = $form->getData();

            $updatedUser->setName($name);
            $this->userRepository->save($updatedUser, true);

            return $this->redirectToRoute('app_users_index');
        }

        return $this->render('users/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function delete($id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new Response("User not found!");
        }

        $this->userRepository->remove($user, true);

        return $this->redirectToRoute('app_users_index');
    }

    public function show($id): Response
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new Response("User not found!");
        }

        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }

    public function addToGroup(int $userId, int $groupId): Response 
    {
        $user = $this->userRepository->find($userId);
        $group = $this->groupRepository->find($groupId);

        if (!$user || !$group) {
            return new Response('User or group not found!');
        }

        if (!$user->getGroups()->contains($group)) {
            return new Response('User is already asigned to this group!');
        }

        $user->addGroup($group);
        $this->userRepository->addToGroup($user, true);

        return $this->redirectToRoute('app_users_index');
    }

    public function removeFromGroup(int $userId, int $groupId): Response 
    {
        $user = $this->userRepository->find($userId);
        $group = $this->groupRepository->find($groupId);

        if (!$user || !$group) {
            return new Response('User or group not found!');
        }

        if (!$user->getGroups()->contains($group)) {
            return new Response('User is not asigned to this group!');
        }

        $user->removeGroup($group);
        $this->userRepository->removeFromGroup($user, true);

        return $this->redirectToRoute('app_users_index');
    }
}
