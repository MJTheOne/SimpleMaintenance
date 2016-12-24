<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 24-12-16
 * Time: 20:25
 */

namespace CoderGeek\Bundle\MaintenanceBundle\Listener;


use CoderGeek\Bundle\MaintenanceBundle\Drivers\DriverFactory;

class MaintenanceListener
{
    protected $driverFactory;

    protected $whitelistIps;

    protected $route;

    protected $httpCode;

    protected $httpStatus;

    public function __construct(
        DriverFactory $driverFactory,
        $ips = [],
        $route = [],
        $httpCode = null,
        $httpStatus = null
    ) {
        $this->driverFactory = $driverFactory;
        $this->whitelistIps = $ips;
        $this->route = $route;
        $this->httpCode = $httpCode;
        $this->httpStatus = $httpStatus;
    }
}