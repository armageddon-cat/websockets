<?php

/**
 * Created by PhpStorm.
 * User: sera
 * Date: 01.07.2016
 * Time: 16:36
 */
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
    public function getId()
    {
        return (string)$this->id;
    }
    
    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = (string)$id;
    }
    
    /**
     * @return mixed
     */
    public function getX()
    {
        return (int)$this->x;
    }
    
    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = (int)$x;
    }
    
    /**
     * @return mixed
     */
    public function getY()
    {
        return (int)$this->y;
    }
    
    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = (int)$y;
    }
    
    /**
     * @return mixed
     */
    public function getDirection()
    {
        return (int)$this->direction;
    }
    
    /**
     * @param mixed $direction
     */
    public function setDirection($direction)
    {
        $this->direction = (int)$direction;
    }
    
    /**
     * @return mixed
     */
    public function getStatus()
    {
        return (string)$this->status;
    }
    
    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = (string)$status;
    }
}