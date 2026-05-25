<?php
namespace App\Service\Register;

use App\DTO\RegistrationDTO;
use App\Entity\User;
use App\Mapper\UserMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
        private UserMapper $mapper
    ) {}

    public function register(RegistrationDTO $dto): User
    {
        $user = $this->mapper->toEntity($dto);
        $hashed = $this->hasher->hashPassword($user, $dto->password);
        $user->setPassword($hashed);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}