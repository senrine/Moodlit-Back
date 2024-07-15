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
        $user->setEmail($data['email']);

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user);
        return $user->serialize();
    }

    public function getUsers() : array
    {
        $users = $this->userRepository->findAll();
        $serializedUsers = [];
        foreach ($users as $user) {
            $serializedUsers[] = $user->serialize();
        }
        return $serializedUsers;
    }

    public function getUserById(int $id) : array
    {
        $user= $this->userRepository->find($id)->serialize();
        if(!$user) {
            throw new \InvalidArgumentException('User not found');
        }
        return $user;
    }
}