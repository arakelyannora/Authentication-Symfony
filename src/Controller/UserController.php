<?php

namespace App\Controller;

use App\Responses\UserResponseMapper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends ApplicationController
{
    public function __construct(
	    private SerializerInterface $serializer,
	    private UserResponseMapper $responseMapper
    ) {
    	parent::__construct($this->serializer);
    }

    /**
     * @Route (
     *     "/users/me",
     *     methods={"GET"}
     * )
     */
    public function getMe(): Response
    {
        return $this->jsonOkResponse($this->responseMapper->mapFromDomain($this->getAuthenticatedUser()));
    }
}