<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ValidationException extends GeneralException
{
	/**
	 * @var string[]
	 */
	protected array $errors;

	protected $code = Response::HTTP_BAD_REQUEST;

	/**
	 * @param string[] $errors
	 *
	 * @return self
	 */
	public static function errors(array $errors): self
	{
		$validationException = new ValidationException("Validation failed");
		$validationException->errors = $errors;

		return $validationException;
	}

	/**
	 * @return string[]
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}
}