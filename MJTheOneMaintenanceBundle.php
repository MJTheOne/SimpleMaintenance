<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 23-12-16
 * Time: 21:39
 */

namespace MJTheOne\Bundle\MaintenanceBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle as BaseBundle;

class MJTheOneMaintenanceBundle extends BaseBundle
{
    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return strtr(__DIR__, '\\', '/');
    }
}
