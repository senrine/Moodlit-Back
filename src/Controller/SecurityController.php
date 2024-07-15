<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\ValidationException;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SecurityController extends AbstractController
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @throws ValidationException
     */
    #[Route('/register', name: 'app_user_register', methods: ["POST"])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        return $this->json(["user"=>$this->userService->registerUser($data)]);
    }

    #[Route('/login', name: 'app_user_login', methods: ["POST"])]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'success' => false,
                'message' => "Invalid credentials"
            ]);
        }

        return $this->json([
            "user" => $user->serialize()
        ]);
    }

    #[Route('/logout', name: 'app_user_logout', methods: ["POST"])]
    public function logout(): void
    {}
}
