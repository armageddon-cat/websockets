<?php
declare(strict_types=1);
namespace Tanks;


class BulletRegistry // todo refactor with iterator
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
    public static function removeBullet(string $id)
    {
        unset(self::$storage[$id]);
    }
    
    /**
     * @return Bullet[]
     */
    public static function getStorage() : array
    {
        return self::$storage;
    }
    
    /**
     *
     */
    public static function unsetStorage()
    {
        self::$storage = [];
    }
    
    public static function moveBullets()
    {
//        var_dump(__FUNCTION__);
        $bullets = self::getStorage();
//        var_dump($bullets);
        foreach ($bullets as $bullet) {
//            var_dump(__FUNCTION__ . 'foreach');
            $bullet->move();
            $bulX = $bullet->getX();
            $bulY = $bullet->getY();
            if ($bulX < Canvas::CANVAS_START || $bulX > Canvas::CANVAS_SIZE ||
                $bulY < Canvas::CANVAS_START || $bulY > Canvas::CANVAS_SIZE) {
                self::removeBullet($bullet->getId());
            }
        }
    }
        
}