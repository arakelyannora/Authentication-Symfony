<?php

namespace App\Controller;

use App\Responses\ResponseEntity;
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

	public function jsonOkResponse(ResponseEntity $responseEntity): JsonResponse
	{
		return new JsonResponse(
			$this->serializer->serialize($responseEntity, 'json'),
			Response::HTTP_OK,
			[],
			true
		);
	}
}