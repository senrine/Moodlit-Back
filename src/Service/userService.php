<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class userService
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function registerUser($data) : array
    {
        $user = new User();
        $user->setName($data['name']);
        $user->setPassword($data['password']);
        $user->setEmail($data['email']);
        $this->userRepository->save($user);
        return $user->serialize();
    }

    public function loginUser(#[CurrentUser] ?User $user) : array{ return $user->serialize();}
}