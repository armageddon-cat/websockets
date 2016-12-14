<?php
declare(strict_types=1);
    
namespace Tanks;

/**
 * Class TankMove
 * @package Tanks
 */
class TankMove
{
    private $time;
    private $x;
    private $y;
    
    /**
     * TankMove constructor.
     *
     * @param Tank $tank
     */
    public function __construct(Tank $tank)
    {
        $this->setTime($tank->getTime());
        $this->setX($tank->getX());
        $this->setY($tank->getY());
    }
    
    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }
    
    /**
     * @param \DateTime $time
     */
    protected function setTime(\DateTime $time): void
    {
        $this->time = $time;
    }
    
    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }
    
    /**
     * @param int $x
     */
    protected function setX(int $x): void
    {
        $this->x = $x;
    }
    
    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }
    
    /**
     * @param int $y
     */
    protected function setY(int $y): void
    {
        $this->y = $y;
    }
}
