<?php

namespace App\Requests;

use App\ServiceCommands\UserRegisterCommand;

class RegisterUserRequestMapper
{
    public function mapToUserRegisterCommand(RegisterUserRequest $registerUserRequest): UserRegisterCommand
    {
        return new UserRegisterCommand($registerUserRequest->email, $registerUserRequest->password, $registerUserRequest->fullname);
    }
}