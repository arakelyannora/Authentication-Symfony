<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserRequest
{
	/**
	 * @Assert\NotBlank(message="The email should not be blank.")
	 * @Assert\Email(message="{{ value }} is not valid email address.")
	 */
	public string $email;

	/**
	 * @Assert\NotBlank(message="The password should not be blank.")
	 */
	public string $password;

    /**
     * @Assert\NotBlank(message="The fullname should not be blank.")
     * @Assert\Type(type="string", message="The fullname should be string.")
     */
    public string $fullname;
}