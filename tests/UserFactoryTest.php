<?php

use App\Common\UuidGeneratorInterface;
use App\Entity\User;
use App\Factory\UserFactory;
use \PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactoryTest extends TestCase
{
    private UuidGeneratorInterface $uuidGenerator;
    private UserPasswordHasherInterface $userPasswordHasher;
    private UserFactory $userFactory;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->uuidGenerator = $this->createMock(UuidGeneratorInterface::class);
        $this->userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->userFactory = new UserFactory($this->uuidGenerator, $this->userPasswordHasher);
    }

    public function testUserCreate()
    {
        $email = 'some-email';
        $fullName = 'full-name';
        $password = 'some-password';
        $hashedPassword = 'passwordHashed';

        $this->userPasswordHasher->method('hashPassword')->willReturn($hashedPassword);

        $createdUser = $this->userFactory->create($email, $password, $fullName);

        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertEquals($email, $createdUser->getEmail());
        $this->assertEquals($fullName, $createdUser->getFullName());
        $this->assertEquals($hashedPassword, $createdUser->getPassword());
    }
}