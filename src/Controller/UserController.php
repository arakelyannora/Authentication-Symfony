<?php

namespace App\Controller;

use App\Entity\User;
use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\UserNotFoundException;
use App\Requests\RegisterUserRequest;
use App\Responses\UserResponseMapper;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends ApplicationController
{
    public function __construct(
    	private EntityManagerInterface $entityManager,
	    private SerializerInterface $serializer,
	    private UserResponseMapper $responseMapper
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
	    $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse("created", Response::HTTP_CREATED);
    }

	/**
	 * @Route(
	 *     "/users/{id}"
	 * ),
	 * methods={"GET"}
	 */
    public function getUserById(string $id)
    {
    	$repository = $this->entityManager->getRepository(User::class);
    	$user = $repository->findOneBy(
    		[
    			"id" => $id
		    ]
	    );

    	if (!$user instanceof User) {
			throw UserNotFoundException::withId($id);
	    }

    	$userResponse = $this->responseMapper->mapFromDomain($user);

    	return $this->jsonOkResponse($userResponse);
    }
}