<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use QuadruPHP\Router\Exceptions\InvalidNameException;
use QuadruPHP\Router\Router;

class RouterTest extends TestCase
{
    /**
     * Tests the ability to add a new route to the route collection and verifies that the route exists.
     *
     * @return void
     */
    public function testCanAddRoute(): void
    {
        $collection = new Router();
        $collection->add('GET', '/home', 'HomeController::index');

        $this->assertTrue($collection->has('GET', '/home'));
    }

    /**
     * Tests the ability to find a route by its name from the route collection and verifies the presence of expected keys in the result.
     *
     * @return void
     */
    public function testCanFindRoute(): void
    {
        $collection = new Router();
        $collection->add('GET', '/home', 'HomeController::index', 'Homepage');

        $array = $collection->findByName('Homepage');

        $this->assertArrayHasKey('method', $array);
        $this->assertArrayHasKey('path', $array);
        $this->assertArrayHasKey('callable', $array);
    }

    /**
     * Tests that attempting to find a non-existing route in the collection returns null or an empty result.
     *
     * @return void
     */
    public function testReturnsNullForNonExistingRoute(): void
    {
        $collection = new Router();

        $result = $collection->find('GET', '/non-existing');

        $this->assertArrayNotHasKey('callable', $result);
    }

    /**
     * Tests that the callable associated with a route is correctly invoked and returns the expected result.
     *
     * @return void
     */
    public function testCallablesAreCalled()
    {
        $collection = new Router();
        $collection->add('GET', '/home', function () { return 'Hello World!'; });

        $result = $collection->find('GET', '/home');

        $this->assertEquals('Hello World!', $result['callable']());
    }

    public function testRouteNameExist()
    {
        $this->expectException(InvalidNameException::class);
        $this->expectExceptionMessage('Route with name "Homepage" already exists.');

        $collection = new Router();
        $collection->add('GET', '/home', 'HomeController::index', 'Homepage');
        $collection->add('GET', '/test', 'HomeController::test', 'Homepage');

    }
}
