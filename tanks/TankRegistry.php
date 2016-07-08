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
    public function getTank(string $id) : Tank
    {
        return self::$storage[$id];
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
     * @return array
     */
    public static function getStorageJSON() : array
    {
        return json_encode(self::$storage);
    }
}