<?php

namespace App\Repository;

use App\Entity\User;
use App\Exceptions\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUserById(int $id): User
    {
		$user = $this->findOneBy(
			[
				"id" => $id
			]
		);

		if (!$user instanceof User) {
			throw UserNotFoundException::withId($id);
		}

		return $user;
    }
}
