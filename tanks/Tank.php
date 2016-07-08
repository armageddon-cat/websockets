<?php
declare(strict_types=1);
namespace Tanks;

use stdClass;

class Tank
{
    private $id;
    private $x;
    private $y;
    private $direction;
    private $status;
    const TANK_STEP = 10;
    const CODE_LEFT_ARROW = 37;
    const CODE_UP_ARROW = 38;
    const CODE_RIGHT_ARROW = 39;
    const CODE_DOWN_ARROW = 40;
    
    public function __construct(stdClass $tank)
    {
        $this->setId($tank->id);
        $this->setStatus($tank->status);
        $this->setX($tank->x);
        $this->setY($tank->y);
    }
    
    public function moveTank() {
        if($this->getDirection() === self::CODE_LEFT_ARROW) {
            $this->x -= self::TANK_STEP;
        }
        if($this->getDirection() === self::CODE_UP_ARROW) {
            $this->y -= self::TANK_STEP;
        }
        if($this->getDirection() === self::CODE_RIGHT_ARROW) {
            $this->x += self::TANK_STEP;
        }
        if($this->getDirection() === self::CODE_DOWN_ARROW) {
            $this->y += self::TANK_STEP;
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * @return mixed
     */
    public function getId() : string
    {
        return (string)$this->id;
    }
    
    /**
     * @param mixed $id
     */
    public function setId(string $id)
    {
        $this->id = (string)$id;
    }
    
    /**
     * @return mixed
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
     * @return string
     */
    public function getStatus() : string
    {
        return (string)$this->status;
    }
    
    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = (string)$status;
    }
}