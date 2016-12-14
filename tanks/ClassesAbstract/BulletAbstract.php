<?php
declare(strict_types=1);

namespace ClassesAbstract;

use Tanks\Bullet;
use Tanks\Tank;

/**
 * Class BulletAbstract
 * @package ClassesAbstract
 */
abstract class BulletAbstract extends PointAbstract
{
    protected $direction;
    protected $path; // in future must be an object made by DI
    protected $pathTime; // in future must be an object made by DI
    protected $tank;
    protected $shootTime;
    public const BULLET_STEP                    = 10; // pixel
    public const BULLET_DELAY_REAL              = 20;// milliseconds (thousandths of a second)
    public const BULLET_DELAY_REAL_MICROSECONDS = self::BULLET_DELAY_REAL * 1000;// microseconds
    public const BULLET_SIZE                    = 10;
    public const BULLET_FLY_TIME                = self::BULLET_DELAY_REAL * (Canvas::CANVAS_SIZE / Bullet::BULLET_STEP); // 20 * ( 800 / 10 ) = 1600 milsec = 1.6sec
    public const BULLET_FLY_TIME_MICROSECONDS   = self::BULLET_FLY_TIME * 1000; // microseconds
    public const BULLET_FLY_TIME_SECONDS        = self::BULLET_FLY_TIME / 1000; // seconds
    public const CLIENT_FIELDS = ['x', 'y'];
    
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
    protected function setDirection(int $direction): void
    {
        $this->direction = $direction;
    }
    
    /**
     * @return array
     */
    public function getPath(): array
    {
        return $this->path;
    }
    
    /**
     * @return void
     */
    abstract protected function setPath(): void;
    
    /**
     * @return \DateTime[]
     */
    public function getPathTime(): array
    {
        return $this->pathTime;
    }
    
    /**
     * @return void
     */
    abstract protected function setPathTime(): void;
    
    /**
     * @return Tank
     */
    public function getTank(): Tank
    {
        return $this->tank;
    }
    
    /**
     * @param Tank $tank
     */
    protected function setTank(Tank $tank): void
    {
        $this->tank = $tank;
    }
    
    /**
     * @return \DateTime
     */
    public function getShootTime(): \DateTime
    {
        return $this->shootTime;
    }
    
    /**
     * @param \DateTime $shootTime
     */
    protected function setShootTime(\DateTime $shootTime): void
    {
        $this->shootTime = $shootTime;
    }
}
