<?php
declare(strict_types = 1);

namespace ClassesAbstract;


/**
 * Class PointAbstract
 * @package ClassesAbstract
 */
abstract class PointAbstract
{
    protected $id;
    protected $x;
    protected $y;
    
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
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
    public function setX(int $x)
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
    public function setY(int $y)
    {
        $this->y = $y;
    }
}