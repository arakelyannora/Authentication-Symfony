<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserRequest
{
	/**
	 * @Assert\NotBlank(message="email should not be blank.")
	 * @Assert\Email(message="{{ value }} is not valid email address.")
	 */
	public string $email;

	/**
	 * @Assert\NotBlank(message="password should not be blank.")
	 */
	public string $password;
}