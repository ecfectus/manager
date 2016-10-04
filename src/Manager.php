<?php
/**
 * Created by PhpStorm.
 * User: leemason
 * Date: 04/10/16
 * Time: 18:48
 */

namespace Ecfectus\Manager;


trait Manager
{
    /**
     * @var array
     */
    protected $drivers = [];

    /**
     * @var array
     */
    protected $creators = [];

    /**
     * Define what each driver must implement.
     *
     * @return array
     */
    abstract public function getImplements() : array;

    /**
     * Define the default driver.
     *
     * @return string
     */
    abstract public function getDefaultDriver() : string;

    /**
     * Call/Create the driver requested.
     *
     * @param string|null $driver
     * @return mixed
     */
    public function driver(string $driver = null)
    {
        $driver = $driver ?? $this->getDefaultDriver();

        if (! isset($this->drivers[$driver])) {
            $instance = $this->createDriver($driver);
            $implements = $this->getImplements();
            $instanceImplements = (array) class_implements($instance);

            foreach($implements as $interface){
                if(!isset($instanceImplements[$interface])){
                    throw new \ErrorException('Class ' . get_class($instance) . '  does not meet the Interface requirements set out in ' . self::class . '.');
                }
            }

            $this->drivers[$driver] = $instance;
        }
        return $this->drivers[$driver];
    }

    /**
     * Create/Return the custom driver instance.
     *
     * @param string|null $driver
     * @return mixed
     */
    private function createDriver(string $driver = null)
    {
        if (isset($this->creators[$driver])) {
            return $this->callCreator($driver);
        } else{
            $method = 'create' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $driver))) . 'Driver';
            if (method_exists($this, $method)) {
                return $this->$method();
            }
        }
        throw new \InvalidArgumentException("Driver [$driver] not supported.");
    }

    /**
     * Invoke the creator added via the extend() method.
     *
     * @param $driver
     * @return mixed
     */
    protected function callCreator($driver)
    {
        return $this->creators[$driver]();
    }

    /**
     * Add a new custom creator the creators array for usage.
     *
     * @param $driver
     * @param callable $callback
     * @return $this
     */
    public function extend($driver, callable $callback)
    {
        $this->creators[$driver] = $callback;
        return $this;
    }

    /**
     * Pass through calls on the manager class down to the actual instance.
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }

}