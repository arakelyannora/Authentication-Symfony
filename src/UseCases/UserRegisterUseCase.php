<?php

namespace App\UseCases;

use App\ServiceCommands\UserRegisterCommand;

interface UserRegisterUseCase
{
    public function register(UserRegisterCommand $userRegisterCommand);
}