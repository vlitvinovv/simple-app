<?php

namespace App\Users\Application\Query\FindUserByEmail;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\Users\Application\DTO\UserDTO;
use App\Users\Domain\Repository\UserRepositoryInterface;

class FindUserByEmailQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function __invoke(FindUserByEmailQuery $findUserByEmailQuery): ?UserDTO
    {
        $user = $this->userRepository->findByEmail($findUserByEmailQuery->email);

        if (!$user) {
            return null;
        }

        return UserDTO::fromEntity($user);
    }
}