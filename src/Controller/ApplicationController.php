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
use Pagerfanta\Adapter\AdapterInterface;
use Hateoas\Configuration\Route;
use Hateoas\HateoasBuilder;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\UrlGenerator\SymfonyUrlGenerator;
use JMS\Serializer\SerializationContext;
use Pagerfanta\Pagerfanta;

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

    public function jsonPaginatedCollection(
        AdapterInterface $adapter,
        callable $callable,
        string $route,
        array $params = [],
        ?int $page = 1,
        ?int $limit = 10
    ) {
        if ($page < 1 || is_null($page)) {
            $page = 1;
        }

        if ($limit < 1 || is_null($limit)) {
            $limit = 10;
        }

        $hateoas = HateoasBuilder::create()
                ->setUrlGenerator(null, new SymfonyUrlGenerator($this->container->get('router')))
                ->build();
        
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage($limit);
        $pager->setCurrentPage($page);
        $pagerfantaFactory = new PagerfantaFactory();
		$currentPageResults = (array)$pager->getCurrentPageResults();
        $paginatedCollection = $pagerfantaFactory->createRepresentation(
            $pager,
            new Route($route, $params),
            new CollectionRepresentation(array_map($callable, $currentPageResults))
        );

        return new JsonResponse(
            $hateoas->serialize($paginatedCollection, 'json'),
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