<?php

use App\Entity\User;
use App\Responses\UserResponse;
use App\Responses\UserResponseMapper;
use PHPUnit\Framework\TestCase;

class UserResponseMapperTest extends TestCase
{
    public function testMapFromDomain()
    {
        $userResponseMapper = new UserResponseMapper();
        $user = $this->createMock(User::class);
        $user->method('getEmail')->willReturn('some-email');
        $user->method('getFullName')->willReturn('some-fullName');

        $response = $userResponseMapper->mapFromDomain($user);
        $this->assertInstanceOf(UserResponse::class, $response);
        $this->assertEquals('some-email', $response->getEmail());
        $this->assertEquals('some-fullName', $response->getFullname());
    }
}