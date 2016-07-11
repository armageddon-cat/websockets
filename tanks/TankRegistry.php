<?php
declare(strict_types=1);
namespace Tanks;

class TankRegistry
{
    /**
    *  @param array $storage
    */
    private static $storage = [];
    
    /**
     * @param Tank $tank
     */
    public static function addTank(Tank $tank) {
        self::$storage[$tank->getId()] = $tank;
    }
    
    /**
     * @param $id
     *
     * @return Tank
     */
    public static function getTank(string $id) : Tank
    {
        return self::$storage[$id];
    }
    
    /**
     * @param $id
     *
     * @return bool
     */
    public static function checkTank(string $id) : bool
    {
        return isset(self::$storage[$id]);
    }
    
    /**
     * @param $id
     */
    public function removeTank(string $id)
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
    
    /**
     * @return string
     */
    public static function getStorageJSON() : string
    {
        $result = [];
        foreach (self::$storage as $item) {
            $result[] = (string)$item;
        }
        return json_encode($result);
    }
    
    public static function unsetBullets()
    {
        foreach (self::$storage as $item) {
            $item->unsetBullet();
        }
    }
}