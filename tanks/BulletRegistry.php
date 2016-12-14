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
    protected static $instance;
    /**
     * Move each bullet with one step forward
     * and unset it from registry and tank
     * if it is out of border
     */
    public static function moveBullets(): void
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
     * @return void
     */
    public static function fireEach(): void
    {
        $bulletsStorage = self::getInstance();
        $bulletsStorage->rewind();
        while ($bulletsStorage->valid()) {
            $bullet = $bulletsStorage->current();
            if ($bullet->isHit()) {
                self::remove($bullet);
                $bullet->getTank()->unsetBullet();
            }
            $bulletsStorage->next();
        }
    }

    /**
     * @return Bullet
     */
    public function current(): Bullet
    {
        return parent::current();
    }
}
