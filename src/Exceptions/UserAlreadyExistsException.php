<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UserAlreadyExistsException extends UserException
{
	protected $code = Response::HTTP_BAD_REQUEST;

	public static function fromEmail(string $email): self
	{
		return new UserAlreadyExistsException(sprintf("User with %s email already exists.", $email));
	}
}