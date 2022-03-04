<?php

namespace App\Configuration;

use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\UserException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorController
{
	private const ERROR_MAP = [
		ValidationException::class => [
			"code" => "VALIDATION_FAILED",
			"message" => "Validation failed"
		],
		UserException::class => [
			"code" => "USER_INVALID_DATA",
			"message" => "User invalid data"
		],
		UserNotFoundException::class => [
			"code" => "NOT_FOUND",
			"message" => "User not found"
		],
		\Exception::class => [
			"code" => "SYSTEM_ERROR",
			"message" => "System error"
		],
		\Throwable::class => [
			"code" => "SYSTEM_ERROR",
			"message" => "System error"
		]
	];

	public function show(\Throwable $exception)
	{
		$code = $exception->getCode();

		if (! $code) {
			$code = Response::HTTP_INTERNAL_SERVER_ERROR;
		}

		$body = [
			"errors" => []
		];

		if (isset(self::ERROR_MAP[get_class($exception)])) {
			$body["code"] = self::ERROR_MAP[get_class($exception)]["code"];
			$body["message"] = self::ERROR_MAP[get_class($exception)]["message"];
		}

		if ($exception instanceof ValidationException) {
			$body["errors"] = $exception->getErrors();
		} else if ($exception instanceof UserException){
			$body["errors"] = $exception->getMessage();
		} else {
			$code = Response::HTTP_INTERNAL_SERVER_ERROR;
			$body["code"] = self::ERROR_MAP[\Exception::class]["code"];
			$body["message"] = self::ERROR_MAP[\Exception::class]["message"];
		}

		$response = new JsonResponse(
			$body,
			$code
		);

		$response->headers->set('Content-Type', 'application/json; charset=UTF-8');

		return $response;
	}
}