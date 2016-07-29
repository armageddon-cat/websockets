<?php
declare(strict_types=1);
namespace Tanks;

class Tank
{
    private $id;
    private $x;
    private $y;
    private $direction;
    private $status;
    private $bullet;
    const TANK_STEP = 10;
    const TANK_SIZE = 100;
    const TANK_HIT_AREA = 20;
    const DEAD = 0;
    const ALIVE = 1;
    const TANK_BARREL_OFFSET_VALUE = 60;
    const DEFAULT_TANK_CORS_X = 150;
    const DEFAULT_TANK_CORS_Y = 150;
    const DEFAULT_TANK_DIRECTION = Canvas::CODE_UP_ARROW;
    
    public function __construct()
    {
        $this->setId(Guid::newRef());
        $this->setStatus(Tank::ALIVE);
        $this->setX(self::DEFAULT_TANK_CORS_X);
        $this->setY(self::DEFAULT_TANK_CORS_X);
        $this->setDirection(self::DEFAULT_TANK_DIRECTION);
    }
    
    public function moveTank() {
        $direction = $this->getDirection();
        if($direction === Canvas::CODE_LEFT_ARROW) {
            $this->x -= self::TANK_STEP;
        }
        if($direction === Canvas::CODE_UP_ARROW) {
            $this->y -= self::TANK_STEP;
        }
        if($direction === Canvas::CODE_RIGHT_ARROW) {
            $this->x += self::TANK_STEP;
        }
        if($direction === Canvas::CODE_DOWN_ARROW) {
            $this->y += self::TANK_STEP;
        }
    }
    
    /**
     * @return string
     */
    public function getId() : string
    {
        return (string)$this->id;
    }
    
    /**
     * @param mixed $id
     */
    protected function setId(string $id)
    {
        $this->id = (string)$id;
    }
    
    /**
     * @return int
     */
    public function getX() : int
    {
        return (int)$this->x;
    }
    
    /**
     * @param int $x
     */
    public function setX(int $x)
    {
        $this->x = (int)$x;
    }
    
    /**
     * @return int
     */
    public function getY() : int
    {
        return (int)$this->y;
    }
    
    /**
     * @param int $y
     */
    public function setY(int $y)
    {
        $this->y = (int)$y;
    }
    
    /**
     * @return int
     */
    public function getDirection() : int
    {
        return (int)$this->direction;
    }
    
    /**
     * @param int $direction
     */
    public function setDirection(int $direction)
    {
        $this->direction = (int)$direction;
    }
    
    /**
     * @return int
     */
    public function getStatus() : int
    {
        return (int)$this->status;
    }
    
    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = (int)$status;
    }
    
    /**
     * @return string
     */
    public function __toString() : string
    {
        $result = [];
        foreach ($this as $key => $value) {
            $result[$key] = $value;
            if (is_object($value)) {
                $result[$key] = (string)$value;
            }
        }
        return json_encode($result);
    }
    
    /**
     * @return Bullet
     */
    public function getBullet() : Bullet
    {
        return $this->bullet;
    }
    
    /**
     * @param Bullet $bullet
     */
    public function setBullet(Bullet $bullet)
    {
        $this->bullet = $bullet;
    }
    
    public function unsetBullet()
    {
        unset($this->bullet);
    }
    
    /**
     * @return int
     */
    public function getTankCenterX() : int
    {
        $tankCenterX = $this->getX() + Tank::TANK_SIZE * 0.5;
        return (int)$tankCenterX;
    }
    
    /**
     * @return int
     */
    public function getTankCenterY() : int
    {
        $tankCenterY = $this->getY() + Tank::TANK_SIZE * 0.5;
        return (int)$tankCenterY;
    }
    
    /**
     * @param string $type
     *
     * @return int
     */
    private function calculateOffset(string $type) : int
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
        
        return (int)$offsetValue;
    }
    
    /**
     * @return int
     */
    public function getTankBarrelX() : int
    {
        $tankBarrel = $this->getTankCenterX() + $this->calculateOffset(Canvas::AXIS_X);
        return (int)$tankBarrel;
    }
    
    /**
     * @return int
     */
    public function getTankBarrelY() : int
    {
        $tankBarrel = $this->getTankCenterY() + $this->calculateOffset(Canvas::AXIS_Y);
        return (int)$tankBarrel;
    }
}