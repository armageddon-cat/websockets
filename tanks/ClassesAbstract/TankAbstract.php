<?php
declare(strict_types=1);

namespace ClassesAbstract;

use Tanks\Bullet;
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
    public const TANK_STEP = 10;
    public const TANK_SIZE = 100;
    public const TANK_HIT_AREA = 20;
    public const TANK_LENGTH = 73; // px
    public const TANK_WIDTH = 36; // px
    public const TANK_LENGTH_HALF = self::TANK_LENGTH * 0.5;
    public const TANK_WIDTH_HALF = self::TANK_WIDTH * 0.5;
    public const DEAD = 0;
    public const ALIVE = 1;
    public const TANK_BARREL_LENGTH = 27;
    public const TANK_BARREL_OFFSET_VALUE_LENGTH = self::TANK_LENGTH_HALF + self::TANK_BARREL_LENGTH;
    public const TANK_PADDING_LEFT_BARREL_UP = 30;
    public const TANK_PADDING_RIGHT_BARREL_UP = 34;
    public const DEFAULT_TANK_CORS_X = 150;
    public const DEFAULT_TANK_CORS_Y = 150;
    public const DEFAULT_TANK_DIRECTION = Canvas::CODE_UP_ARROW;
    public const CLIENT_FIELDS = ['id', 'x', 'y', 'direction'];
    
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
    protected function setTime(\DateTime $time): void
    {
        $this->time = $time;
    }
    
    
    /**
     * @param Tank $tank
     */
    protected function setRoute(Tank $tank): void
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
