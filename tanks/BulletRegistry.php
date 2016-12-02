<?php
declare(strict_types=1);
namespace Tanks;


use ClassesAbstract\AbstractRegistry;

/**
 * Class BulletRegistry
 * @package Tanks
 */
class BulletRegistry extends AbstractRegistry
{
    protected static $instance = null;
    /**
     * Move each bullet with one step forward
     * and unset it from registry and tank
     * if it is out of border
     */
    public static function moveBullets()
    {
        $bulletsStorage = self::getInstance();
        $bulletsStorage->rewind();
        while ($bulletsStorage->valid()) {
            $bullet = $bulletsStorage->current();
            $bullet->move();
            if (!$bullet->inBounds($bullet)) {
                self::remove($bullet);
                $bullet->getTank()->unsetBullet();
            }
            $bulletsStorage->next();
        }
    }
    
    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::setInstance(new self);
        }
        
        return self::$instance;
    }
    
    /**
     * @return Bullet
     */
    public function current(): Bullet
    {
        return parent::current();
    }
    
}