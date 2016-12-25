<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 24-12-16
 * Time: 19:52
 */

namespace CoderGeek\Bundle\MaintenanceBundle\Drivers;

/**
 * Class FileDriver
 * @package CoderGeek\Bundle\MaintenanceBundle\Drivers
 */
class FileDriver
{
    protected $options;
    protected $filePath;

    public function __construct(array $options = [])
    {
        if (null !== $options) {
            $this->filePath = $options['filePath'];
        }

        $this->options = $options;
    }

    public function lock()
    {
        if (!$this->isExists()) {
            return $this->lockIt();
        }

        return false;
    }

    public function unlock()
    {
        if ($this->isExists()) {
            return $this->unlockIt();
        }

        return false;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function canYouPass()
    {
        return $this->isExists();
    }

    public function isExists()
    {
        if (file_exists($this->filePath)) {
            return true;
        }

        return false;
    }

    protected function lockIt()
    {
        return (fopen($this->filePath, 'w+'));
    }

    protected function unlockIt()
    {
        return @unlink($this->filePath);
    }
}