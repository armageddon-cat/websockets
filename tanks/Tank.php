<?php
declare(strict_types=1);
namespace Tanks;

use ClassesAbstract\Canvas;
use ClassesAbstract\TankAbstract;

/**
 * Class Tank
 * @package Tanks
 */
class Tank extends TankAbstract
{
    /**
     * Tank constructor.
     *
     * @param \DateTime $time
     *
     * @throws \UnexpectedValueException
     */
    public function __construct(\DateTime $time)
    {
        $this->setId(Guid::newRef());
        $this->setStatus(Tank::ALIVE);
        $this->setX(self::DEFAULT_TANK_CORS_X);
        $this->setY(self::DEFAULT_TANK_CORS_X);
        $this->setDirection(self::DEFAULT_TANK_DIRECTION);
        $this->setTime($time);
        $this->setRoute($this);
    }
    
    /**
     * @param \DateTime $moveTime
     */
    public function moveTank(\DateTime $moveTime): void
    {
        $this->setTime($moveTime);
        $direction = $this->getDirection();
        if ($direction === Canvas::CODE_LEFT_ARROW) {
            $this->x -= self::TANK_STEP;
        }
        if ($direction === Canvas::CODE_UP_ARROW) {
            $this->y -= self::TANK_STEP;
        }
        if ($direction === Canvas::CODE_RIGHT_ARROW) {
            $this->x += self::TANK_STEP;
        }
        if ($direction === Canvas::CODE_DOWN_ARROW) {
            $this->y += self::TANK_STEP;
        }
        $this->getRoute()->addMove($this);
    }
    
    /**
     * Fields send to client
     * id
     * direction
     * x
     * y
     * bullet
     * bullet.x
     * bullet.y
     *
     * @return string
     */
    public function prepareToClientJson(): string
    {
        $result = [];
        foreach ($this as $property => $value) {
            if (in_array($property, TankAbstract::CLIENT_FIELDS, true)) {
                $result[$property] = $value;
            }
            if ($value instanceof Bullet) {
                $result[$property] = $value->prepareToClientJson();
            }
        }
        return json_encode($result);
    }
    
    public function unsetBullet(): void
    {
        unset($this->bullet);
    }
    
    /**
     * @return int
     */
    public function getTankCenterX(): int
    {
        return (int)($this->getX() + Tank::TANK_SIZE * 0.5);
    }
    
    /**
     * @return int
     */
    public function getTankCenterY(): int
    {
        return (int)($this->getY() + Tank::TANK_SIZE * 0.5);
    }
    
    /**
     * @param string $type
     *
     * @return int
     */
    private function calculateOffset(string $type): int
    {
        $direction   = $this->getDirection();
        $offsetValue = 0;
        
        if ($direction === Canvas::CODE_LEFT_ARROW && $type === Canvas::AXIS_X) {
            $offsetValue = -self::TANK_BARREL_OFFSET_VALUE;
        }
        if ($direction === Canvas::CODE_UP_ARROW && $type === Canvas::AXIS_Y) {
            $offsetValue = -self::TANK_BARREL_OFFSET_VALUE;
        }
        if ($direction === Canvas::CODE_RIGHT_ARROW && $type === Canvas::AXIS_X) {
            $offsetValue = self::TANK_BARREL_OFFSET_VALUE;
        }
        if ($direction === Canvas::CODE_DOWN_ARROW && $type === Canvas::AXIS_Y) {
            $offsetValue = self::TANK_BARREL_OFFSET_VALUE;
        }
        
        return $offsetValue;
    }
    
    /**
     * @return int
     */
    public function getTankBarrelX(): int
    {
        return (int)($this->getTankCenterX() + $this->calculateOffset(Canvas::AXIS_X));
    }
    
    /**
     * @return int
     */
    public function getTankBarrelY(): int
    {
        return (int)($this->getTankCenterY() + $this->calculateOffset(Canvas::AXIS_Y));
    }
}
