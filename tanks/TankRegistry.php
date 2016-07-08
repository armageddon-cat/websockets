<?php
namespace Tanks;
/**
 * Created by PhpStorm.
 * User: sera
 * Date: 01.07.2016
 * Time: 16:38
 */
class TankRegistry
{
    private static $storage = [];
    
    /**
     * @param \Tank $tank
     */
    public static function addTank(Tank $tank) {
        self::$storage[$tank->getId()] = $tank;
    }
    
    /**
     * @param $id
     *
     * @return mixed
     */
    public function getTank($id)
    {
        return self::$storage[$id];
    }
    
    /**
     * @param $id
     */
    public function removeTank($id)
    {
        unset(self::$storage[$id]);
    }
    
    /**
     * @return array
     */
    public static function getStorage()
    {
        return self::$storage;
    }
    
    /**
     * @return array
     */
    public static function getStorageJSON()
    {
        return json_encode(self::$storage);
    }
}