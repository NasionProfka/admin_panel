<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Validation\UserValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiUsersController extends AbstractController
{
    private $userRepository;
    private $groupRepository;

    public function __construct (UserRepository $userRepository, GroupRepository $groupRepository) {
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
    } 

    public function create(Request $request, UserValidation $userValidation): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        $errors = $userValidation->validate($body);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $form = $this->createForm(UserFormType::class, new User());
        $newUser = $form->getData(); 

        $newUser->setName($body['name']);
        $this->userRepository->save($newUser, true);    

        return new JsonResponse(['message' => 'User added successfully'], JsonResponse::HTTP_CREATED);
    }

    public function delete($id): JsonResponse
    {   
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'User not found!'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->userRepository->remove($user, true);

        return new JsonResponse(['message' => 'User deleted successfully'], JsonResponse::HTTP_OK);
    }

    public function addToGroup(
        int $userId, 
        int $groupId
    ): JsonResponse {
        $user = $this->userRepository->find($userId);
        $group = $this->groupRepository->find($groupId);

        if (!$user || !$group) {
            return new JsonResponse('User or group not found!');
        }

        if ($user->getGroups()->contains($group)) {
            return new JsonResponse('User is already asigned to this group!', Response::HTTP_FORBIDDEN);
        }

        $user->addGroup($group);
        $this->userRepository->addToGroup($user, true);

        return new JsonResponse(['message' => 'User added to the group successfully!'], JsonResponse::HTTP_OK);
    }

    public function removeFromGroup( 
        int $userId, 
        int $groupId
    ): JsonResponse {
        $user = $this->userRepository->find($userId);
        $group = $this->groupRepository->find($groupId);

        if (!$user || !$group) {
            return new JsonResponse('User or group not found!', Response::HTTP_NOT_FOUND);
        }

        if (!$user->getGroups()->contains($group)) {
            return new JsonResponse('User is not asigned to this group!', Response::HTTP_NOT_FOUND);
        }

        $user->removeGroup($group);
        $this->userRepository->removeFromGroup($user, true);

        return new JsonResponse('User removed from the group successfully', Response::HTTP_OK);
    }
}
