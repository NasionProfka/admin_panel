<?php

namespace App\Controller\Api;

use App\Entity\Group;
use App\Form\GroupFormType;
use App\Repository\GroupRepository;
use App\Validation\GroupValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiGroupsController extends AbstractController
{
    private $groupRepository;

    public function __construct (GroupRepository $groupRepository) {
        $this->groupRepository = $groupRepository;
    } 

    public function create(Request $request, GroupValidation $groupValidation): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        $errors = $groupValidation->validate($body);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $form = $this->createForm(GroupFormType::class, new Group());
        $newGroup = $form->getData(); 

        $newGroup->setName($body['name']);
        $this->groupRepository->save($newGroup, true);    

        return new JsonResponse(['message' => 'Group added successfully'], JsonResponse::HTTP_CREATED);
    }

    public function delete($id): JsonResponse
    {   
        $group = $this->groupRepository->find($id);

        if (!$group) {
            return new JsonResponse(['message' => 'Group not found!'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($group->getUsers()->count() > 0) {
            return new JsonResponse(['message' => 'Group can not be deleted, because it is not empty!'], JsonResponse::HTTP_FORBIDDEN);
        }

        $this->groupRepository->remove($group, true);

        return new JsonResponse(['message' => 'Group deleted successfully'], JsonResponse::HTTP_OK);
    }
}
