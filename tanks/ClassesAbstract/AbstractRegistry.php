<?php
declare(strict_types=1);

namespace ClassesAbstract;

/**
 * Class AbstractRegistry
 * @package ClassesAbstract
 */
abstract class AbstractRegistry extends \ArrayIterator
{
    protected static $instance;

    /**
     * @return \self
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::setInstance(new static);
        }

        return static::$instance;
    }
    
    /**
     * @param \ArrayIterator $instance
     */
    public static function setInstance(\ArrayIterator $instance): void
    {
        static::$instance = $instance;
    }
    
    /**
     * @param PointAbstract $point
     */
    public static function add(PointAbstract $point): void
    {
        static::getInstance()->offsetSet($point->getId(), $point);
    }
        
    /**
     * @param string $id
     *
     * @return bool
     */
    public static function exists(string $id): bool
    {
        return static::getInstance()->offsetExists($id);
    }
    
    /**
     * @param PointAbstract $point
     */
    public static function remove(PointAbstract $point): void
    {
        static::getInstance()->offsetUnset($point->getId());
    }
    
    public static function unsetRegistry(): void
    {
        static::$instance = null;
    }
}
