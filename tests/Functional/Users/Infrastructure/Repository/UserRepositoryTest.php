<?php

namespace App\Tests\Functional\Users\Infrastructure\Repository;

use App\Tests\Resource\Fixture\UserFixture;
use App\Users\Domain\Factory\UserFactory;
use App\Users\Infrastructure\Repository\UserRepository;
use Faker\Factory;
use Faker\Generator;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase
{
    private UserRepository $repository;

    private Generator $faker;

    private AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = static::getContainer()->get(UserRepository::class);
        $this->faker = Factory::create();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
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

    public function test_user_found_successfully(): void
    {
        $executor = $this->databaseTool->loadFixtures([UserFixture::class]);
        $user = $executor->getReferenceRepository()->getReference(UserFixture::REFERENCE);

        $dbUser = $this->repository->findByUlid($user->getUlid());

        $this->assertEquals($user->getUlid(), $dbUser->getUlid());
    }
}
