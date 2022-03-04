<?php

namespace App\Configuration;

use App\Exceptions\UserAlreadyExistsException;
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
		UserAlreadyExistsException::class => [
			"code" => "USER_EXISTS",
			"message" => "User already exists"
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
		} else if ($exception instanceof UserAlreadyExistsException){
			$body["errors"] = $exception->getMessage();
		} else {
			$code = Response::HTTP_INTERNAL_SERVER_ERROR;
			$body["code"] = self::ERROR_MAP[\Exception::class]["code"];
			$body["message"] = self::ERROR_MAP[\Exception::class]["message"];
		}

		return new JsonResponse(
			$body,
			$code
		);
	}
}