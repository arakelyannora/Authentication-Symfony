<?php

namespace App\Factory;

use App\Common\UuidGeneratorInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
	public function __construct(
		private UuidGeneratorInterface $uuidGenerator,
		private UserPasswordHasherInterface $passwordHasher
	) {
	}

    public function create(string $email, string $password): User
    {
		$user = new User(
			$this->uuidGenerator->getUuid(),
			$email
		);

		$hashedPassword = $this->passwordHasher->hashPassword($user, $password);
		$user->setPassword($hashedPassword);

		return $user;
    }
}