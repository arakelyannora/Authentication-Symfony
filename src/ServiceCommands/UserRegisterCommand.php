<?php

namespace App\ServiceCommands;

class UserRegisterCommand
{
    public function __construct(
        private string $email,
        private string $password,
        private string $fullName
    ) {
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
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}