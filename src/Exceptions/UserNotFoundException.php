<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UserNotFoundException extends UserException
{
	protected $code = Response::HTTP_BAD_REQUEST;

	public static function withId(int $id): self
	{
		return new self(sprintf("User with %d id doesn't exists", $id));
	}
}