<?php

namespace Ecfectus\Manager\Test;

use Ecfectus\Manager\Manager;
use PHPUnit\Framework\TestCase;

class StubManager{
    use Manager;

    public function getDefaultDriver() : string
    {
        return 'default';
    }

    public function createDefaultDriver()
    {
        return new StubClass();
    }
}

class StubClass{
    public function toBeCalled()
    {
        return 'success';
    }
}


class ManagerTest extends TestCase
{

    public function testDefaultDriverIsCalled()
    {

        $manager = new StubManager();

        $this->assertEquals(new StubClass(), $manager->driver());
    }

    public function testCustomDriverIsCalled()
    {

        $manager = new StubManager();

        $class = new class{};

        $manager->extend('name', function() use ($class){
            return $class;
        });

        $this->assertSame($class, $manager->driver('name'));
    }

    public function testDriverMethodIsCalled()
    {

        $manager = new StubManager();

        $this->assertEquals('success', $manager->toBeCalled());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFailureWithWrongDriver()
    {

        $manager = new StubManager();

        $manager->driver('doesntexist');
    }
}
