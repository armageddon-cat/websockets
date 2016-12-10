<?php
declare(strict_types=1);
namespace Tanks;

use ClassesAbstract\AbstractRegistry;

/**
 * Class TankRegistry
 *
 * dead tanks have status dead. but not removed from registry.
 * but don't give them to front
 * maybe in future some logic on dead tanks
 *
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
            if (self::getInstance()->current()->getStatus() !== Tank::DEAD) {
                $result[] = self::getInstance()->current()->prepareToClientJson();
            }
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
        if ($message->isNewDirection()) {
            $tank = TankRegistry::get($message->getId());
            $tank->setDirection($message->getNewDirection());
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
