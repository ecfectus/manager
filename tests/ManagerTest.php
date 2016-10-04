<?php

namespace Ecfectus\Manager\Test;

use Ecfectus\Manager\Manager;
use PHPUnit\Framework\TestCase;

class StubManager{
    use Manager;

    public function getImplements() : array
    {
        return [StubInterface::class];
    }

    public function getDefaultDriver() : string
    {
        return 'default';
    }

    public function createFailsDriver()
    {
        return new StubClassTwo();
    }

    public function createDefaultDriver()
    {
        return new StubClass();
    }
}

interface StubInterface{
    public function toBeCalled();
}

class StubClass implements StubInterface{
    public function toBeCalled()
    {
        return 'success';
    }
}

class StubClassTwo{
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

        $class = new class implements StubInterface{
            public function toBeCalled()
            {
                return 'success';
            }
        };

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

    /**
     * @expectedException \ErrorException
     */
    public function testFailureForDriverWithoutInterface()
    {

        $manager = new StubManager();

        $manager->driver('fails');
    }
}
