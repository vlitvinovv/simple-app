<?php

namespace App\Tests\Functional\Users\Application\Query\FindUserByEmail;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Tests\Resource\Fixture\UserFixture;
use App\Users\Application\DTO\UserDTO;
use App\Users\Application\Query\FindUserByEmail\FindUserByEmailQuery;
use App\Users\Application\Query\FindUserByEmail\FindUserByEmailQueryHandler;
use App\Users\Domain\Entity\User;
use App\Users\Domain\Repository\UserRepositoryInterface;
use Faker\Factory;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FindUserByEmailQueryHandlerTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->queryBus = static::getContainer()->get(QueryBusInterface::class);
        $this->userRepository = static::getContainer()->get(UserRepositoryInterface::class);
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->faker = Factory::create();
    }

    public function test_user_created_when_command_executed()
    {
        $referenceRepository = $this->databaseTool->loadFixtures([UserFixture::class])->getReferenceRepository();

        /** @var User $user */
        $user = $referenceRepository->getReference(UserFixture::REFERENCE);
        $query = new FindUserByEmailQuery($user->getEmail());

        $userDTO = $this->queryBus->execute($query);

        $this->assertEquals(UserDTO::fromEntity($user), $userDTO);
    }

    public function test_user_is_not_found_by_email()
    {
        $query = new FindUserByEmailQuery('unexisting_email@email.com');

        $userDTO = $this->queryBus->execute($query);

        $this->assertEquals(null, $userDTO);
    }
}
