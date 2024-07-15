<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    #[Route('/users', name: 'app_get_users', methods: ["GET"])]
    public function getUsers(): JsonResponse
    {
        return $this->json(["users"=>$this->userService->getUsers()]);
    }

    #[Route('/user/{id}', name: 'app_get_user', methods: ["GET"])]
    public function getUserById(int $id): JsonResponse
    {
        return $this->json(["user"=>$this->userService->getUserById($id)]);
    }
}
