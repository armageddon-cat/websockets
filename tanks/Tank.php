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
        $this->setStatus(self::ALIVE);
        $this->setX($this->getRandomRespPoint());
        $this->setY($this->getRandomRespPoint());
        $this->setDirection(self::DEFAULT_TANK_DIRECTION);
        $this->setTime($time);
        $this->setRoute($this);
    }
    
    /**
     * @param \DateTime $moveTime
     *
     * @return void
     */
    public function moveTank(\DateTime $moveTime): void
    {
        $this->setTime($moveTime);
        $this->changeCoordinates();
        $this->getRoute()->addMove($this);
    }
    
    /**
     * Fields send to client
     * 1 id
     * 2 direction
     * 3 x
     * 4 y
     * if bullet exists:
     * 5 bullet.x
     * 6 bullet.y
     *
     * @return string
     */
    public function prepareToClientJson(): string
    {
        $result = [];
        foreach ($this as $property => $value) {
            if (in_array($property, self::CLIENT_FIELDS, true)) {
                $result[$property] = $value;
            }
            if ($value instanceof Bullet) {
                $result[$property] = $value->prepareToClientJson();
            }
        }
        return json_encode($result);
    }

    /**
     * @return void
     */
    public function unsetBullet(): void
    {
        unset($this->bullet);
    }
    
    /**
     * @return int
     */
    public function getTankCenterX(): int
    {
        switch ($this->getDirection()) {
            case Canvas::CODE_RIGHT_ARROW:
                return $this->getX() + self::getTankWidthHalf();
            case Canvas::CODE_LEFT_ARROW:
                return $this->getX() + self::getTankWidthHalf() + self::TANK_BARREL_LENGTH;
            case Canvas::CODE_UP_ARROW:
            case Canvas::CODE_DOWN_ARROW:
                return $this->getX() + self::getTankWidthHalf() + self::TANK_PADDING_LEFT_BARREL_UP;
            default:
                return 0; // todo exceptional case. maybe some work here in future
        }
    }
    
    /**
     * @return int
     */
    public function getTankCenterY(): int
    {
        switch ($this->getDirection()) {
            case Canvas::CODE_DOWN_ARROW:
                return $this->getY() + self::getTankWidthHalf();
            case Canvas::CODE_UP_ARROW:
                return $this->getY() + self::getTankWidthHalf() + self::TANK_BARREL_LENGTH;
            case Canvas::CODE_LEFT_ARROW:
            case Canvas::CODE_RIGHT_ARROW:
                return $this->getY() + self::getTankWidthHalf() + self::TANK_PADDING_LEFT_BARREL_UP;
            default:
                return 0; // todo exceptional case. maybe some work here in future
        }
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
            $offsetValue = -self::TANK_BARREL_OFFSET_VALUE_LENGTH;
        }
        if ($direction === Canvas::CODE_UP_ARROW && $type === Canvas::AXIS_Y) {
            $offsetValue = -self::TANK_BARREL_OFFSET_VALUE_LENGTH;
        }
        if ($direction === Canvas::CODE_RIGHT_ARROW && $type === Canvas::AXIS_X) {
            $offsetValue = self::TANK_BARREL_OFFSET_VALUE_LENGTH;
        }
        if ($direction === Canvas::CODE_DOWN_ARROW && $type === Canvas::AXIS_Y) {
            $offsetValue = self::TANK_BARREL_OFFSET_VALUE_LENGTH;
        }
        
        return (int)$offsetValue;
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

    /**
     * @return bool
     */
    public function isAlive(): bool
    {
        return $this->getStatus() === self::ALIVE;
    }

    /**
     * @return int
     */
    public function getRandomRespPoint(): int
    {
        return random_int(Canvas::CANVAS_START, Canvas::CANVAS_SIZE - self::TANK_SIZE);
    }

    /**
     * @return void
     */
    private function changeCoordinates(): void
    {
        switch ($this->getDirection()) {
            case Canvas::CODE_LEFT_ARROW:
                $this->decrementXByStep();
                break;
            case Canvas::CODE_UP_ARROW:
                $this->decrementYByStep();
                break;
            case Canvas::CODE_RIGHT_ARROW:
                $this->incrementXByStep();
                break;
            case Canvas::CODE_DOWN_ARROW:
                $this->incrementYByStep();
                break;
        }
    }

    /**
     * @return void
     */
    private function decrementXByStep(): void
    {
        $this->x -= self::TANK_STEP;
    }

    /**
     * @return void
     */
    private function decrementYByStep(): void
    {
        $this->y -= self::TANK_STEP;
    }

    /**
     * @return void
     */
    private function incrementXByStep(): void
    {
        $this->x += self::TANK_STEP;
    }

    /**
     * @return void
     */
    private function incrementYByStep(): void
    {
        $this->y += self::TANK_STEP;
    }

    /**
     * @return int
     */
    public static function getTankWidthHalf(): int
    {
        return (int)(self::TANK_WIDTH * 0.5);
    }

    /**
     * @return int
     */
    public static function getTankLengthHalf(): int
    {
        return (int)(self::TANK_LENGTH * 0.5);
    }
}
