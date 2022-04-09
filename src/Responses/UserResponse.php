<?php

namespace App\Responses;

class UserResponse extends ResponseEntity
{
	private string $email;

    private string $fullname;

	public function __construct(string $email, string $fullname)
	{
		$this->email = $email;
        $this->fullname = $fullname;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

    /**
     * @return string
     */
    public function getFullname(): string
    {
        return $this->fullname;
    }
}