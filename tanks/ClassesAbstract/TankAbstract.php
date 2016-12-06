<?php
declare(strict_types=1);

namespace ClassesAbstract;

use Tanks\Bullet;
use Tanks\Canvas;
use Tanks\Tank;
use Tanks\TankMoveRoute;

/**
 * Class TankAbstract
 * @package ClassesAbstract
 */
abstract class TankAbstract extends PointAbstract
{
    protected $direction;
    protected $status;
    protected $bullet;
    protected $time;
    protected $route;
    const TANK_STEP                = 10;
    const TANK_SIZE                = 100;
    const TANK_HIT_AREA            = 20;
    const DEAD                     = 0;
    const ALIVE                    = 1;
    const TANK_BARREL_OFFSET_VALUE = 60;
    const DEFAULT_TANK_CORS_X      = 150;
    const DEFAULT_TANK_CORS_Y      = 150;
    const DEFAULT_TANK_DIRECTION   = Canvas::CODE_UP_ARROW;
    
    /**
     * @return int
     */
    public function getDirection(): int
    {
        return $this->direction;
    }
    
    /**
     * @param int $direction
     */
    public function setDirection(int $direction): void
    {
        $this->direction = $direction;
    }
    
    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
    
    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
    
    /**
     * @return Bullet
     */
    public function getBullet(): Bullet
    {
        return $this->bullet;
    }
    
    /**
     * @param Bullet $bullet
     */
    public function setBullet(Bullet $bullet): void
    {
        $this->bullet = $bullet;
    }
    
    
    /**
     * Current position time
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }
    
    /**
     * Current position time
     *
     * @param \DateTime $time
     */
    public function setTime(\DateTime $time): void
    {
        $this->time = $time;
    }
    
    
    /**
     * @param Tank $tank
     */
    public function setRoute(Tank $tank): void
    {
        $this->route = new TankMoveRoute($tank);
    }
    
    /**
     * @return TankMoveRoute
     */
    public function getRoute(): TankMoveRoute
    {
        return $this->route;
    }
}
