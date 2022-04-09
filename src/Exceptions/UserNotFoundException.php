<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UserNotFoundException extends UserException
{
	protected $code = Response::HTTP_BAD_REQUEST;

	public static function withId(string $id): self
	{
		return new self(sprintf("User with %d id doesn't exists", $id));
	}

    public static function byEmail(string $email): self
    {
        return new self(sprintf("User not found with %s email", $email), Response::HTTP_NOT_FOUND);
    }
}