<?php
declare(strict_types=1);

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
    protected function setId(string $id): void
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
