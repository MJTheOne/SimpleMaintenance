<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 24-12-16
 * Time: 20:25
 */

namespace CoderGeek\Bundle\MaintenanceBundle\Listener;

use CoderGeek\Bundle\MaintenanceBundle\Drivers\DriverFactory;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class MaintenanceListener
{
    protected $maintenanceTemplate;

    protected $templating;

    protected $driverFactory;

    protected $whitelistIps;

    protected $route;

    protected $httpCode;

    protected $httpStatus;

    private $handleResponse;

    public function __construct(
        EngineInterface $templating,
        DriverFactory $driverFactory,
        $maintenanceTemplate = null,
        $ips = [],
        $route = [],
        $httpCode = null,
        $httpStatus = null
    ) {
        $this->templating = $templating;
        $this->driverFactory = $driverFactory;
        $this->maintenanceTemplate = $maintenanceTemplate;
        $this->whitelistIps = $ips;
        $this->route = $route;
        $this->httpCode = $httpCode;
        $this->httpStatus = $httpStatus;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (count($this->whitelistIps) !== 0 && in_array($this->getRealIp(), $this->whitelistIps)) {
            return;
        }

        $route = $request->get('_route');
        if (null !== $this->route && preg_match('{'.$this->route.'}', $route)) {
            return;
        }

        $driver = $this->driverFactory->getDriver();
        if ($driver->canYouPass()) {
            $this->handleResponse = true;
        }

        return;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($this->handleResponse && $this->httpCode !== null) {
//            $response = $event->getResponse();
//            $response->setStatusCode($this->httpCode, $this->httpStatus);
//            $response->setContent($this->maintenanceTemplate); // not good method

            $response = $this->templating->renderResponse($this->maintenanceTemplate, ['param' => 'variable']);
            $event->setResponse($response);

//            $response = new Response(
//                $this->maintenanceTemplate,
//                ['param' => 'variable']
//            );
//            $response->setStatusCode($this->httpCode, $this->httpStatus);
//            $event->setResponse($response);
//            $event->stopPropagation();
        }
    }

    private function getRealIp()
    {
        switch (true) {
            case (!empty($_SERVER['HTTP_X_REAL_IP'])) :
                return $_SERVER['HTTP_X_REAL_IP'];
            case (!empty($_SERVER['HTTP_CLIENT_IP'])) :
                return $_SERVER['HTTP_CLIENT_IP'];
            case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) :
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            default :
                return $_SERVER['REMOTE_ADDR'];
        }
    }
}