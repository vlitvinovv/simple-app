<?php

namespace App\Tests\Functional\Users\Infrastructure\Repository;

use App\Users\Domain\Factory\UserFactory;
use App\Users\Infrastructure\Repository\UserRepository;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase
{
    private UserRepository $repository;

    private Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = static::getContainer()->get(UserRepository::class);
        $this->faker = Factory::create();
    }

    public function test_user_added_successfully(): void
    {
        $email = $this->faker->email();
        $password = $this->faker->password();

        $user = (new UserFactory())->create($email, $password);

        $this->repository->add($user);

        $dbUser = $this->repository->findByUlid($user->getUlid());
        $this->assertEquals($user->getUlid(), $dbUser->getUlid());
    }
}
