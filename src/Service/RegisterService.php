<?php
namespace App\Service;

use App\DTO\RegisterDTO;
use App\Entity\User;
use App\Mapper\UserMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
        private UserMapper $mapper
    ) {}

    public function register(RegisterDTO $dto): User
    {
        $user = $this->mapper->toEntity($dto);
        $hashed = $this->hasher->hashPassword($user, $dto->password);
        $user->setPassword($hashed);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}