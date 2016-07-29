<?php
declare(strict_types=1);
namespace Tanks;


class Bullet
{
    private $id;
    private $direction;
    private $path; // in future must be an object made by DI
    private $tank;
    private $x;
    private $y;
    const BULLET_STEP = 10; // pixel
    const BULLET_DELAY = 500; // milliseconds (thousandths of a second)
    const BULLET_SIZE = 10;
    
    public function __construct(\stdClass $object)
    {
        $this->setId($object->id);
        $this->setTank(TankRegistry::getTank($this->getId()));
        $this->setDirection($this->getTank()->getDirection());
        $this->setPath();
        $this->setX($this->getTank()->getTankBarrelX());
        $this->setY($this->getTank()->getTankBarrelY());
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
     * @return array
     */
    public function getPath() : array
    {
        return (array)$this->path;
    }
    
    /**
     * @internal param array $path
     */
    public function setPath()
    {
        $direction = $this->getDirection();
        $tank = $this->getTank();
        $startPosition = 0;
        $endPosition = 0;
        if ($direction === Canvas::CODE_LEFT_ARROW || $direction === Canvas::CODE_RIGHT_ARROW) {
            $startPosition = $tank->getTankBarrelX();
        }
        if ($direction === Canvas::CODE_UP_ARROW || $direction === Canvas::CODE_DOWN_ARROW) {
            $startPosition = $tank->getTankBarrelY();
        }
        if ($direction === Canvas::CODE_LEFT_ARROW || $direction === Canvas::CODE_DOWN_ARROW) {
            $endPosition = Canvas::CANVAS_START;
        }
        if ($direction === Canvas::CODE_UP_ARROW || $direction === Canvas::CODE_RIGHT_ARROW) {
            $endPosition = Canvas::CANVAS_SIZE;
        }
        $this->path = range($startPosition, $endPosition);
    }
    
    public function getBulletPositionByTime($startTime, $position, $direction)
    {
        
    }
    
    public function bulletTrajectory($startPosition, $direction)
    {
        
    }
    
    public function checkIntersection()
    {
//        var_dump('checkIntersectionstart');
//        var_dump(json_encode($this->getPath()));
        $direction = $this->getDirection();
        $tanks = TankRegistry::getStorage();
        /** @var Tank $tank */
        foreach ($tanks as $tank) {
            if ($this->getId() == $tank->getId()) {
                continue;
            }
            if ($direction === Canvas::CODE_LEFT_ARROW || $direction === Canvas::CODE_RIGHT_ARROW) {
                for ($i = -Tank::TANK_HIT_AREA; $i <= Tank::TANK_HIT_AREA; $i++) { // intersection area = tank center +- 20
//                    var_dump($tank->getTankCenterY());
//                    var_dump($tank->getTankCenterY()+$i);
//                    var_dump($tank->getTankCenterX()+$i);
//                    var_dump($this->getY());
                    if ($tank->getTankCenterY()+$i == $this->getY() && in_array($tank->getTankCenterX()+$i, $this->getPath())) {
//                        var_dump('tankstatusupdated');
                        $tank->setStatus(Tank::DEAD);
                        break;
                    }
                }
            }
            if ($direction === Canvas::CODE_UP_ARROW || $direction === Canvas::CODE_DOWN_ARROW) {
                for ($i = -Tank::TANK_HIT_AREA; $i <= Tank::TANK_HIT_AREA; $i++) { // intersection area = tank center +- 20
                    if ($tank->getTankCenterX()+$i == $this->getX() && in_array($tank->getTankCenterY()+$i, $this->getPath())) {
                        $tank->setStatus(Tank::DEAD);
                        break;
                    }
                }
            }
        }
//        var_dump('checkIntersectionend');
    }
    
    /**
     * @return string
     */
    public function getId() : string
    {
        return (string)$this->id;
    }
    
    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = (string)$id;
    }
    
    /**
     * @return mixed
     */
    public function getTank() : Tank
    {
        return $this->tank;
    }
    
    /**
     * @param mixed $tank
     */
    public function setTank(Tank $tank)
    {
        $this->tank = $tank;
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
     * @return string
     */
    public function __toString() : string
    {
        $result = [];
        foreach ($this as $key => $value) {
            if (is_object($value)) {
                continue;
            }
            if($key === 'path') {
                continue; // todo refactor
            }
            
            $result[$key] = $value;
        }
        return json_encode($result);
    }
}