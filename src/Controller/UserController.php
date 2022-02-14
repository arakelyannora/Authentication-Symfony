<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(
     *     "/users"
     * ),
     * methods = {"POST"}
     */
    public function register(Request $request)
    {
        $request1 = json_decode($request->getContent(), true);
        $email = $request1['email'];
        $password = $request1['password'];

        $user = new User($email, $password);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new Response($this->json("created"), Response::HTTP_CREATED);
    }
}