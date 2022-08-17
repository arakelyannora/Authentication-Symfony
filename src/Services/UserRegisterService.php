<?php

namespace App\Services;

use App\Exceptions\UserAlreadyExistsException;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\ServiceCommands\UserRegisterCommand;
use App\UseCases\UserRegisterUseCase;
use Doctrine\ORM\EntityManagerInterface;

class UserRegisterService implements UserRegisterUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private UserFactory $userFactory,
        private EntityManagerInterface $entityManager
    ) {

    }

    public function register(UserRegisterCommand $command)
    {
        $user = $this->userRepository->getUserByEmail($command->getEmail());

        if (!is_null($user)) {
            throw UserAlreadyExistsException::fromEmail($command->getEmail());
        }

        $user = $this->userFactory->create($command);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}