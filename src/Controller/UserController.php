<?php

namespace App\Controller;

use App\Entity\User;
use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\UserNotFoundException;
use App\Repository\UserRepository;
use App\Requests\RegisterUserRequest;
use App\Responses\UserResponseMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends ApplicationController
{
    public function __construct(
    	private EntityManagerInterface $entityManager,
	    private SerializerInterface $serializer,
	    private UserResponseMapper $responseMapper,
        private UserPasswordHasherInterface $passwordHasher,
	    private UserRepository $userRepository
    ) {
    	parent::__construct($this->serializer);
    }

    /**
     * @Route(
     *     "/users"
     * ),
     * methods = {"POST"}
     */
    public function register(RegisterUserRequest $request)
    {
	    $repository = $this->entityManager->getRepository(User::class);

	    $user = $repository->findOneBy(
        	[
        		"email" => $request->email
	        ]
        );

	    if (!is_null($user)) {
		    throw UserAlreadyExistsException::fromEmail($request->email);
	    }
	    $user = new User($request->email, $request->password);
        $hashedPass = $this->passwordHasher->hashPassword($user, $request->password);
        $user->setPassword($hashedPass);
	    $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse("created", Response::HTTP_CREATED);
    }

	/**
	 * @Route(
	 *     "/users/me/{id}"
	 * ),
	 * methods={"GET"}
	 */
    public function getUserById(int $id): Response
    {
    	$user = $this->userRepository->getUserById($id);
    	$userResponse = $this->responseMapper->mapFromDomain($user);

    	return $this->jsonOkResponse($userResponse);
    }
}