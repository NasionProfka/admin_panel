<?php

namespace App\Controller\Web;

use App\Entity\Group;
use App\Form\GroupFormType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupsController extends AbstractController
{
    private $entityManager;
    private $groupRepository;

    public function __construct (
        EntityManagerInterface $entityManager, 
        GroupRepository $groupRepository
    ) {
        $this->entityManager = $entityManager;
        $this->groupRepository = $groupRepository;
    } 
        
    public function index(): Response
    {
        return $this->render('groups/index.html.twig', [
            'groups' => $this->entityManager->getRepository(Group::class)->findAll(),
        ]);
    }

    public function create(Request $request): Response
    {
        $form = $this->createForm(GroupFormType::class, new Group());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();
            $newGroup = $form->getData();

            $newGroup->setName($name);
            $this->groupRepository->save($newGroup, true);

            return $this->redirectToRoute('app_groups_index');
        }

        return $this->render('groups/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit($id, Request $request): Response
    {
        $group = $this->groupRepository->find($id);
        $form = $this->createForm(GroupFormType::class, $group);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();
            $updatedGroup = $form->getData();

            $updatedGroup->setName($name);
            $this->groupRepository->save($updatedGroup, true);

            return $this->redirectToRoute('app_groups_index');
        }

        return $this->render('groups/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function delete($id): Response
    {
        $group = $this->groupRepository->find($id);

        if (!$group) {
            return new Response("Group not found!");
        }

        if (!$group->getusers()->count() > 0) {
            return new Response('This group can not be deleted, because it has members asigned!');
        }

        $this->groupRepository->remove($group, true);

        return $this->redirectToRoute('app_groups_index');
    }

    public function show($id): Response
    {
        $group = $this->groupRepository->find($id);

        if (!$group) {
            return new Response("Group not found!");
        }

        return $this->render('groups/show.html.twig', [
            'group' => $group,
        ]);
    }
}
