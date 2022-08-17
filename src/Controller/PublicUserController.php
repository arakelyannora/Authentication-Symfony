<?php

namespace App\Controller;

use App\Exceptions\UserAlreadyExistsException;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\Requests\RegisterUserRequest;
use App\Requests\RegisterUserRequestMapper;
use App\Responses\UserResponseMapper;
use App\Services\UserRegisterService;
use App\UseCases\UserRegisterUseCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

class PublicUserController extends ApplicationController
{
    public function __construct(
        private SerializerInterface $serializer,
        private UserResponseMapper $responseMapper,
        private UserRepository $userRepository,
        private UserRegisterService $userRegisterUseCase,
        private RegisterUserRequestMapper $registerUserRequestMapper
    ) {
        parent::__construct($this->serializer);
    }

    /**
     * @Route(
     *     "/users"
     * ),
     * methods = {"POST"}
     */
    public function register(RegisterUserRequest $request): Response
    {
        return $this->jsonCreatedResponse($this->responseMapper->mapFromDomain(
                $this->userRegisterUseCase->register($this->registerUserRequestMapper->mapToUserRegisterCommand($request))
            )
        );
    }

    /**
     * @Route(
     *     "/users/{id}",
     *     requirements={"id"="[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89aAbB][a-f0-9]{3}-[a-f0-9]{12}"}
     * ),
     * methods={"GET"}
     */
    public function getUserById(string $id): Response
    {
        $user = $this->userRepository->getUserById($id);
        $userResponse = $this->responseMapper->mapFromDomain($user);

        return $this->jsonOkResponse($userResponse);
    }
}