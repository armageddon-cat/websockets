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
     * @param \DateTime              $serverTime
     * @param ClientMessageContainer $message
     */
    public static function moveTank(\DateTime $serverTime, ClientMessageContainer $message): void
    {
        if ($message->getNewd() !== null) {
            $tank = TankRegistry::get($message->getId());
            $tank->setDirection($message->getNewd());
            $clientTime = $message->getTime();
            $interval   = $clientTime->diff($serverTime);
            $time       = $serverTime;
            if ((int)$interval->format('%Y%m%d%H%m%s') === 0) { // if difference more than 1 second use server time
                $time = $clientTime;
            }
            
            $tank->moveTank($time);
        }
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
