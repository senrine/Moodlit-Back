<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\ValidationException;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private UserRepository $userRepository;
    private ValidatorInterface $validator;
    public function __construct(UserRepository $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    /**
     * @throws ValidationException
     */
    public function registerUser(array $data) : array
    {
        $user = new User();

        $user->setName($data['name']);
        $user->setEmail($data['email']);

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->setPassword($hashedPassword);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

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