<?php
declare(strict_types=1);
    
namespace Tanks;


class TankMove
{
    private $time;
    private $x;
    private $y;
    
    public function __construct(Tank $tank)
    {
        $this->setTime($tank->getTime());
        $this->setX($tank->getX());
        $this->setY($tank->getY());
    }
    
    /**
     * @return \DateTime
     */
    public function getTime() : \DateTime
    {
        return $this->time;
    }
    
    /**
     * @param \DateTime $time
     */
    public function setTime(\DateTime $time)
    {
        $this->time = $time;
    }
    
    /**
     * @return int
     */
    public function getX() : int
    {
        return $this->x;
    }
    
    /**
     * @param int $x
     */
    public function setX(int $x)
    {
        $this->x = $x;
    }
    
    /**
     * @return int
     */
    public function getY() : int
    {
        return $this->y;
    }
    
    /**
     * @param int $y
     */
    public function setY(int $y)
    {
        $this->y = $y;
    }
}