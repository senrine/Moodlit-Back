<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\userService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        return $this->json($this->userService->registerUser($data));
    }

    #[Route('/logout', name: 'app_user_logout', methods: ["POST"])]
    public function logout(): void
    {}
}
