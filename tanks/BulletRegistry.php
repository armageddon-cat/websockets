<?php
declare(strict_types=1);
namespace Tanks;


class BulletRegistry
{
    /**
     *  @param array $storage
     */
    private static $storage = [];
    
    /**
     * @param Bullet $bullet
     */
    public static function addBullet(Bullet $bullet) {
        self::$storage[$bullet->getId()] = $bullet;
    }
    
    /**
     * @param $id
     *
     * @return Bullet
     */
    public static function getBullet(string $id) : Bullet
    {
        return self::$storage[$id];
    }
    
    /**
     * @param $id
     *
     * @return bool
     */
    public static function checkBullet(string $id) : bool
    {
        return isset(self::$storage[$id]);
    }
    
    /**
     * @param $id
     */
    public function removeBullet(string $id)
    {
        unset(self::$storage[$id]);
    }
    
    /**
     * @return array
     */
    public static function getStorage() : array
    {
        return self::$storage;
    }
}