<?php

namespace App\Factory;

use App\Common\UuidGeneratorInterface;
use App\Entity\User;
use App\ServiceCommands\UserRegisterCommand;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
	public function __construct(
		private UuidGeneratorInterface $uuidGenerator,
		private UserPasswordHasherInterface $passwordHasher
	) {
	}

    public function create(UserRegisterCommand $command): User
    {
		$user = new User(
			$this->uuidGenerator->getUuid(),
			$command->getEmail(),
            $command->getFullName()
		);

		$hashedPassword = $this->passwordHasher->hashPassword($user, $command->getPassword());
		$user->setPassword($hashedPassword);

		return $user;
    }
}