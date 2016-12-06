<?php
declare(strict_types=1);
namespace Tanks;

use ClassesAbstract\AbstractRegistry;

/**
 * Class TankRegistry
 * @package Tanks
 */
class TankRegistry extends AbstractRegistry
{
    protected static $instance;
    /**
     * @return string
     */
    public static function getStorageJSON(): string
    {
        $result = [];
        self::getInstance()->rewind();
        while (self::getInstance()->valid()) {
            $result[] = (string)self::getInstance()->current();
            self::getInstance()->next();
        }
        return json_encode($result);
    }
    
    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::setInstance(new self);
        }
        
        return self::$instance;
    }
    
    /**
     * @return Tank
     */
    public function current(): Tank
    {
        return parent::current();
    }
    
    /**
     * @param string $id
     *
     * @return Tank
     */
    public static function get(string $id): Tank
    {
        return self::getInstance()->offsetGet($id);
    }
}
