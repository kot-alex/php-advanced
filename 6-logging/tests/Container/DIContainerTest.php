<?php

namespace Alex\Weblog\UnitTests\Container;

use Alex\Weblog\Blog\Container\DIContainer;
use Alex\Weblog\Blog\Repositories\UsersRepository\InMemoryUsersRepository;
use Alex\Weblog\Exceptions\NotFoundException;
use PHPUnit\Framework\TestCase;

class DIContainerTest extends TestCase
{
    public function testItThrowsAnExceptionIfCannotResolveType(): void
    {
        $container = new DIContainer();

        $this->expectException(NotFoundException::class);

        $this->expectExceptionMessage(
            'Cannot resolve type: Alex\Weblog\UnitTests\Container\SomeClass'
        );

        $container->get(SomeClass::class);
    }

    public function testItResolvesClassWithoutDependencies(): void
    {
        $container = new DIContainer();

        $object = $container->get(SomeClassWithoutDependencies::class);

        $this->assertInstanceOf(
            SomeClassWithoutDependencies::class,
            $object
        );
    }

    public function testItResolvesClassByContract(): void
    {
        $container = new DIContainer();

        $container->bind(
            UsersRepositoryInterface::class,
            InMemoryUsersRepository::class
        );

        $object = $container->get(UsersRepositoryInterface::class);

        $this->assertInstanceOf(
            InMemoryUsersRepository::class,
            $object
        );
    }

    public function testItReturnsPredefinedObject(): void
    {
        $container = new DIContainer();

        $container->bind(
            SomeClassWithParameter::class,
            new SomeClassWithParameter(42)
        );

        $object = $container->get(SomeClassWithParameter::class);

        $this->assertInstanceOf(
            SomeClassWithParameter::class,
            $object
        );

        $this->assertSame(42, $object->value());
    }

    public function testItResolvesClassWithDependencies(): void
    {
        $container = new DIContainer();

        $container->bind(
            SomeClassWithParameter::class,
            new SomeClassWithParameter(42)
        );

        $object = $container->get(ClassDependingOnAnother::class);

        $this->assertInstanceOf(
            ClassDependingOnAnother::class,
            $object
        );
    }
}
