<?php

namespace App\Responses;

class UserResponse extends ResponseEntity
{
	private string $email;

	public function __construct(string $email)
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}
}