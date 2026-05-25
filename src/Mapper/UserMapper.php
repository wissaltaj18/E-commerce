<?php

namespace App\Mapper;

use App\DTO\RegistrationDTO;
use App\Entity\User;
use App\DTO\LoginResponseDTO;   

class UserMapper
{
    public function toEntity(RegistrationDTO $dto): User
    {
        $user = new User();

        $user->setName($dto->name);
        $user->setEmail($dto->email);

        return $user;
    }
   
}