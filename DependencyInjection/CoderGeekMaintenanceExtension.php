<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 24-12-16
 * Time: 0:30
 */

namespace CoderGeek\Bundle\MaintenanceBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CoderGeekMaintenanceExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('codergeek_maintenance.whitelist.host', $config['whitelist']['host']);
        $container->setParameter('codergeek_maintenance.whitelist.ips', $config['whitelist']['ips']);
        $container->setParameter('codergeek_maintenance.whitelist.route', $config['whitelist']['route']);
        $container->setParameter('codergeek_maintenance.whitelist.attributes', $config['whitelist']['attributes']);
        $container->setParameter('codergeek_maintenance.response.http_code', $config['response']['code']);
        $container->setParameter('codergeek_maintenance.response.http_status', $config['response']['status']);

        $container->setParameter('codergeek_maintenance.driver', $config['driver']);
    }
}
