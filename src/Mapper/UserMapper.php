<?php

namespace App\Mapper;

use App\DTO\RegisterDTO;
use App\Entity\User;
use App\DTO\LoginResponseDTO;   

class UserMapper
{
    public function toEntity(RegisterDTO $dto): User
    {
        $user = new User();

        $user->setName($dto->name);
        $user->setEmail($dto->email);

        return $user;
    }
   
}