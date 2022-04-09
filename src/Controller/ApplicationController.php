<?php

namespace App\Controller;

use App\Entity\User;
use App\Exceptions\WrongUserTypeException;
use App\Responses\ResponseEntity;
use http\Exception\RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ApplicationController extends AbstractController
{
	public function __construct(
		private SerializerInterface $serializer
	) {
	}

    public function getAuthenticatedUser(): User
    {
        $user = parent::getUser();

        if (! $user instanceof User) {
            throw \RuntimeException("Not logged in user");
        }

        return $user;
    }

	public function jsonOkResponse(ResponseEntity $responseEntity): JsonResponse
	{
		return new JsonResponse(
			$this->serializer->serialize($responseEntity, 'json'),
			Response::HTTP_OK,
			[],
			true
		);
	}

    public function jsonCreatedResponse(ResponseEntity $responseEntity): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($responseEntity, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }
}