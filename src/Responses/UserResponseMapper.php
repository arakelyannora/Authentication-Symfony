<?php

namespace App\Responses;

use App\Entity\User;

class UserResponseMapper
{
	public function mapFromDomain(User $user): UserResponse
	{
		return new UserResponse($user->getEmail(), $user->getFullName());
	}
}