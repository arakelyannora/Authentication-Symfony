<?php

use App\Entity\User;
use App\Exceptions\UserAlreadyExistsException;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\Responses\UserResponseMapper;
use App\ServiceCommands\UserRegisterCommand;
use App\Services\UserRegisterService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;

class UserRegisterServiceTest extends TestCase
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private UserRegisterService $userRegisterService;
    private UserFactory $userFactory;
    private UserRegisterCommand $userRegisterCommand;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->userFactory = $this->createMock(UserFactory::class);
        $this->userResponseMapper = $this->createMock(UserResponseMapper::class);
        $this->userRegisterCommand = $this->createMock(UserRegisterCommand::class);
        $this->userRegisterService = new UserRegisterService(
            $this->userRepository,
            $this->userFactory,
            $this->entityManager
        );
    }

    public function testRegisterWhenUserAlreadyExists()
    {
        $user = $this->createMock(User::class);
        $this->userRegisterCommand->method('getEmail')->willReturn('some-email');
        $this->userRepository->method('getUserByEmail')->with($this->userRegisterCommand->getEmail())->willReturn($user);

        $this->expectException(UserAlreadyExistsException::class);
        $this->userRegisterService->register($this->userRegisterCommand);
    }

    public function testRegisterUserWhenUserDoesntExist()
    {
        $user = $this->createMock(User::class);
        $this->userRegisterCommand->method('getEmail')->willReturn('some-email');
        $this->userRepository->method('getUserByEmail')->with($this->userRegisterCommand->getEmail())->willReturn(null);
        $this->userFactory->method('create')->with($this->userRegisterCommand)->willReturn($user);
        $this->userFactory->expects($this->once())->method('create')->with($this->userRegisterCommand);
        $this->entityManager->expects($this->once())->method('persist')->with($user);
        $this->entityManager->expects($this->once())->method('flush');
        $this->userRegisterService->register($this->userRegisterCommand);
    }
}