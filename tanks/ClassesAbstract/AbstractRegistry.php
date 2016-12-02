<?php
declare(strict_types = 1);

namespace ClassesAbstract;


/**
 * Class AbstractRegistry
 * @package ClassesAbstract
 */
abstract class AbstractRegistry extends \ArrayIterator
{
    protected static $instance = null;
    
    /**
     * @return \ArrayIterator
     */
    abstract public static function getInstance();
    
    /**
     * @param \ArrayIterator $instance
     */
    public static function setInstance(\ArrayIterator $instance)
    {
        static::$instance = $instance;
    }
    
    /**
     * @param PointAbstract $point
     */
    public static function add(PointAbstract $point)
    {
        static::getInstance()->offsetSet($point->getId(), $point);
    }
        
    /**
     * @param string $id
     *
     * @return bool
     */
    public static function exists(string $id) : bool
    {
        return static::getInstance()->offsetExists($id);
    }
    
    /**
     * @param PointAbstract $point
     */
    public static function remove(PointAbstract $point)
    {
        static::getInstance()->offsetUnset($point->getId());
    }
    
    public static function unsetRegistry()
    {
        static::$instance = null;
    }
}