<?php
declare(strict_types=1);
namespace Tanks;


class Bullet
{
    private $direction;
    private $path; // in future must be an object made by DI
    
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
     * @return array
     */
    public function getPath() : array
    {
        return (array)$this->path;
    }
    
    /**
     * @param array $path
     */
    public function setPath(array $path)
    {
        $this->path = (array)$path;
    }
}