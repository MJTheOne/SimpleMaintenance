<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 24-12-16
 * Time: 20:08
 */

namespace CoderGeek\Bundle\MaintenanceBundle\Drivers;

/**
 * Class DriverFactory
 * @package CoderGeek\Bundle\MaintenanceBundle\Drivers
 */
class DriverFactory
{
    protected $driverOptions;

    public function __construct(array $driverOptions)
    {
        $this->driverOptions = $driverOptions;
    }

    public function getDriver()
    {
        $class = $this->driverOptions['class'];

        if (!class_exists($class)) {
            $getClass = get_class($this);
            throw new \ErrorException("Class $class not found in $getClass");
        }

        $driver = new $class($this->driverOptions['options']);

        return $driver;
    }
}
