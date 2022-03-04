<?php

namespace App\Controller;

use App\Entity\User;
use App\Exceptions\UserAlreadyExistsException;
use App\Requests\RegisterUserRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {}

    /**
     * @Route(
     *     "/users/{id}"
     * ),
     * methods = {"POST"}
     */
    public function register(RegisterUserRequest $request)
    {
        $user = new User($request->email, $request->password);

        $repository = $this->entityManager->getRepository(User::class);

        $user = $repository->findOneBy(
        	[
        		"email" => $request->email
	        ]
        );

        if (!is_null($user)) {
        	throw UserAlreadyExistsException::fromEmail($request->email);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse("created", Response::HTTP_CREATED);
    }
}