<?php

use App\Common\UuidGeneratorInterface;
use App\Entity\User;
use App\Factory\UserFactory;
use App\ServiceCommands\UserRegisterCommand;
use \PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactoryTest extends TestCase
{
    private UuidGeneratorInterface $uuidGenerator;
    private UserPasswordHasherInterface $userPasswordHasher;
    private UserFactory $userFactory;
    private UserRegisterCommand $userRegisterCommand;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->uuidGenerator = $this->createMock(UuidGeneratorInterface::class);
        $this->userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->userRegisterCommand = $this->createMock(UserRegisterCommand::class);
        $this->userFactory = new UserFactory($this->uuidGenerator, $this->userPasswordHasher);
    }

    public function testUserCreate()
    {
        $email = 'some-email';
        $fullName = 'full-name';
        $password = 'some-password';
        $hashedPassword = 'passwordHashed';

        $this->userPasswordHasher->method('hashPassword')->willReturn($hashedPassword);
        $this->userRegisterCommand->method('getEmail')->willReturn($email);
        $this->userRegisterCommand->method('getFullName')->willReturn($fullName);
        $this->userRegisterCommand->method('getPassword')->willReturn($password);

        $createdUser = $this->userFactory->create($this->userRegisterCommand);

        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertEquals($email, $createdUser->getEmail());
        $this->assertEquals($fullName, $createdUser->getFullName());
        $this->assertEquals($hashedPassword, $createdUser->getPassword());
    }
}