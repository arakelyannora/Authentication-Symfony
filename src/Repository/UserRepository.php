<?php

namespace App\Repository;

use App\Entity\User;
use App\Exceptions\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUserById(string $id): User
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

    public function getUserByEmail(string $email): ?User
    {
        return $this->findOneBy(
            [
                "email" => $email
            ]
        );
    }

    public function getUsers(): QueryAdapter
    {
        return new QueryAdapter($this->createQueryBuilder('u'));
    }
}
