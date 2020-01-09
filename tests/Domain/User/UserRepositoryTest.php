<?php

namespace Directory\Tests\Domain\User;

use Directory\Domain\User\DuplicatedNameException;
use Directory\Domain\User\User;
use Directory\Domain\User\UserNotFoundException;
use Directory\Domain\User\UserProperty;
use Directory\Domain\User\UserRepository;
use PHPUnit\Framework\TestCase;

abstract class UserRepositoryTest extends TestCase
{
    abstract public function getEmptyRepository(): UserRepository;

    public function testOnUserNotFoundThrowException(): void
    {
        $repository = $this->getEmptyRepository();

        $this->expectException(UserNotFoundException::class);

        $repository->findOneById('invalidId');
    }

    public function testOnSaveNewUserShouldBeFoundByItsId(): void
    {
        $repository = $this->getEmptyRepository();

        $user = new User('anId', 'Juan');
        $user->setProperty(new UserProperty('bar', 'foo'));
        $user->setProperty(new UserProperty('color', 'pink'));

        $repository->save($user);

        $foundUser = $repository->findOneById('anId');
        $this->assertNotNull($foundUser);
        $this->assertEquals($user, $foundUser);
    }

    public function testOnUpdateUserItsValuesShouldByUpdated(): void
    {
        $repository = $this->getEmptyRepository();

        $user = new User('anId', 'Juan');
        $user->setProperty(new UserProperty('bar', 'foo'));
        $user->setProperty(new UserProperty('color', 'pink'));
        $user->setProperty(new UserProperty('high', '178'));

        $repository->save($user);

        $user->removeProperty('bar'); // Eliminamos
        $user->setProperty(new UserProperty('color', 'blue')); // Actualizamos
        $user->setProperty(new UserProperty('weigh', '70')); // Añadimos
        $repository->save($user);

        $user = $repository->findOneById('anId');
        $this->assertEquals('blue', $user->getProperty('color')->getValue()); // Ha sido actualizado
        $this->assertNull($user->getProperty('bar')); // Ha sido eliminado
        $this->assertNotNull($user->getProperty('high')); // No ha sido modificado
        $this->assertNotNull($user->getProperty('weigh')); // Ha sido añadido
    }

    public function testOnRemoveUserShouldBeNotFound(): void
    {
        $repository = $this->getEmptyRepository();

        $user = new User('anId', 'Juan');
        $repository->save($user);

        $repository->remove('anId');

        $this->expectException(UserNotFoundException::class);
        $repository->findOneById('anId');
    }

    /**
     * La primera (Juan) tiene las características “color de los ojos” y “color del coche”, ambas con valor “azul claro”
     * La segunda (Irene) tiene las características “color de los ojos”, “color de la casa” y “color del coche” con los valores “azulados”, “azul” y “rojo”
     * La tercera (Manuel) tiene únicamente la característica “color de la casa” con un color “naranja”.
     */
    public function testOnSearchByUserPropsShouldReturnMatches(): void
    {
        $repository = $this->getEmptyRepository();

        $juan = new User('juan', 'Juan');
        $juan->setProperty(new UserProperty('color de ojos', 'azul claro'));
        $juan->setProperty(new UserProperty('color de coche', 'azul claro'));
        $repository->save($juan);

        $irene = new User('irene', 'Irene');
        $irene->setProperty(new UserProperty('color de ojos', 'azulados'));
        $irene->setProperty(new UserProperty('color de casa', 'azul'));
        $irene->setProperty(new UserProperty('color de coche', 'rojo'));
        $repository->save($irene);

        $manuel = new User('manuel', 'Manuel');
        $manuel->setProperty(new UserProperty('color de casa', 'naranja'));
        $repository->save($manuel);

        $matches = $repository->searchByPropertyValue('azul');

        $this->assertCount(2, $matches);
    }

    public function testOnSearchMatchesShouldHaveAllAttributes(): void
    {
        $repository = $this->getEmptyRepository();

        $user = new User('anId', 'My User');
        $user->setProperty(new UserProperty('a', 'a value'));
        $user->setProperty(new UserProperty('b', 'b value'));
        $repository->save($user);

        $matches = $repository->searchByPropertyValue('a');
        $user = $matches[0];

        $this->assertEquals([
            new UserProperty('a', 'a value'),
            new UserProperty('b', 'b value'),
        ], $user->getProperties());
    }

    public function testOnTwoUsersHaveTheSameNameShouldHaveDuplicatedException()
    {
        $repository = $this->getEmptyRepository();

        $userA = new User('anId', 'My User');
        $repository->save($userA);

        $this->expectException(DuplicatedNameException::class);
        $userB = new User('anotherId', 'My User');
        $repository->save($userB);
    }
}
