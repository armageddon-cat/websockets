<?php
declare(strict_types=1);
namespace Tanks;


class Bullet
{
    private $id;
    private $direction;
    private $path; // in future must be an object made by DI
    private $pathTime; // in future must be an object made by DI
    private $tank;
    private $x;
    private $y;
    private $shootTime; // 'U.u' make object in future
    const BULLET_STEP = 10; // pixel
    const BULLET_DELAY_REAL = 20;// milliseconds (thousandths of a second)
    const BULLET_DELAY_REAL_MICROSECONDS = self::BULLET_DELAY * 1000;// microseconds
    const BULLET_DELAY = 500; // milliseconds (thousandths of a second)
    const BULLET_DELAY_MICROSECONDS = self::BULLET_DELAY * 1000; // microseconds
    const BULLET_DELAY_SECONDS = self::BULLET_DELAY / 1000; // seconds
    const BULLET_SIZE = 10;
    const BULLET_FLY_TIME = self::BULLET_DELAY_REAL * (Canvas::CANVAS_SIZE / Bullet::BULLET_STEP); // 20 * ( 800 / 10 ) = 1600 milsec = 1.6sec
    const BULLET_FLY_TIME_MICROSECONDS = self::BULLET_FLY_TIME * 1000; // microseconds
    const BULLET_FLY_TIME_SECONDS = self::BULLET_FLY_TIME / 1000; // seconds

    
    public function __construct(ClientMessageContainer $object)
    {
        $this->setId($object->getId());
        $this->setTank(TankRegistry::getTank($this->getId()));
        $this->setDirection($this->getTank()->getDirection());
        $this->setPath();
        $this->setX($this->getTank()->getTankBarrelX());
        $this->setY($this->getTank()->getTankBarrelY());
        $this->setShootTime($object->getTime());
        $this->setPathTime();
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
    
    public function getBulletPositionByTime($time)
    {
//        $result = array();
//        foreach ($this->getPath() as $path) {
//            $result[$path] = (float)$this->getShootTime() + self::BULLET_DELAY_SECONDS;
//        }
//        return $result;
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
//                    var_dump('start');
//                    var_dump('TCY'.$tank->getTankCenterY().'TCX'.$tank->getTankCenterX().
//                             'TCYi'.($tank->getTankCenterY()+$i).'TCXi'.($tank->getTankCenterX()+$i).
//                             'BY'.($this->getY()).'BX'.($this->getX()));
//                    var_dump('end');
//                    var_dump($tank->getTankCenterY());
//                    var_dump($tank->getTankCenterY()+$i);
//                    var_dump($tank->getTankCenterX()+$i);
//                    var_dump($this->getY());
//                    var_dump($this->getX());
                    if ($tank->getTankCenterY()+$i === $this->getY() && $tank->getTankCenterX()+$i === $this->getX()) {
//                        var_dump('found:'.'TCYi'.($tank->getTankCenterY()+$i).'TCXi'.($tank->getTankCenterX()+$i).
//                                                              'BY'.($this->getY()).'BX'.($this->getX()));
                        /** bullet path bulletTime on current tank position */
                        $bulletTime = $this->getPathTime()[$tank->getTankCenterX() + $i]; // todo add isset // todo refactor in method!!
                        $bTimestamp = (float)$bulletTime->format(DateTimeUser::UNIX_TIMESTAMP_MICROSECONDS);
//                        var_dump($bTimestamp);
//                        $tankCurPosTime = $tank->getTime();
                        $tankRoute =  $tank->getRoute();
                        $index = ($tank->getTankCenterX()) . ':' . ($tank->getTankCenterY());
//                        var_dump($index);
                        if ($tankRoute->checkMove($index)) {
//                            var_dump('move found');
                            $tankCurrentMove = $tankRoute->getMove($index);
                            $tCMTimestamp = (float)$tankCurrentMove->getTime()->format(DateTimeUser::UNIX_TIMESTAMP_MICROSECONDS);
//                            var_dump($tCMTimestamp);
                            $tankMoves = $tankRoute->getTankMoves();
                            while (current($tankMoves) !== $tankCurrentMove) next($tankMoves);
                            $nextTankMove = next($tankMoves); // if tank moved from the shoot time
                            if ($nextTankMove) {
                                $tNMTimestamp = (float)$nextTankMove->getTime()->format(DateTimeUser::UNIX_TIMESTAMP_MICROSECONDS);
                                if ($bTimestamp > $tCMTimestamp && $bTimestamp < $tNMTimestamp ) {
                                    var_dump('tankstatusupdated1');
                                    $tank->setStatus(Tank::DEAD);
                                }
                            }
                            if ($bTimestamp > $tCMTimestamp) {
                                var_dump('tankstatusupdated2');
                                $tank->setStatus(Tank::DEAD);
                            }
                            break;
                        }
//                        $t1 = new \DateTime($bulletTime->format(\DateTime::W3C));
//                        $t2 = new \DateTime($bulletTime->format(\DateTime::W3C));
//                        $timeForwardOffset = $t1->add(new \DateInterval('PT' . 1 . 'S')); // todo 500 milisec instead of 1sec
//                        $timeBackwardOffset = $t2->sub(new \DateInterval('PT' . 1 . 'S')); // todo 500 milisec instead of 1sec
//                        $diff1 = $tankCurPosTime->diff($timeForwardOffset);
//                        $diff2 = $tankCurPosTime->diff($timeBackwardOffset);
//                        var_dump($bulletTime);
//                        var_dump($tankCurPosTime);
////                        var_dump($timeForwardOffset);
//                        var_dump($timeBackwardOffset);
                        
                    }
                }
            }
            if ($direction === Canvas::CODE_UP_ARROW || $direction === Canvas::CODE_DOWN_ARROW) {
                for ($i = -Tank::TANK_HIT_AREA; $i <= Tank::TANK_HIT_AREA; $i++) { // intersection area = tank center +- 20
                    if ($tank->getTankCenterX()+$i == $this->getX() && in_array($tank->getTankCenterY()+$i, $this->getPath())) {
                        /** bullet path bulletTime on current tank position */
                        $bulletTime           = $this->getPathTime()[$tank->getTankCenterY() + $i];
                        $tankCurPosTime = $tank->getTime();
                        $timeForwardOffset = $bulletTime->add(new \DateInterval('PT' . 1 . 'S')); // todo 500 milisec instead of 1sec
                        $timeBackwardOffset = $bulletTime->sub(new \DateInterval('PT' . 1 . 'S')); // todo 500 milisec instead of 1sec
                        var_dump($bulletTime);
                        var_dump($tankCurPosTime);
                        var_dump($timeForwardOffset);
                        var_dump($timeBackwardOffset);
                        if ($tankCurPosTime == $bulletTime || $tankCurPosTime == $timeForwardOffset || $tankCurPosTime == $timeBackwardOffset) { // make interval for bulletTime
//                        $tankCurPosTime === $this->getShootTime(); // todo end this part // i dont think this is needed
                        var_dump('tankstatusupdated');
                            $tank->setStatus(Tank::DEAD);
                        }
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
            if($key === 'path' || $key === 'pathTime') {
                continue; // todo refactor
            }
            
            $result[$key] = $value;
        }
        return json_encode($result);
    }
    
    /**
     * @return \DateTime
     */
    public function getShootTime() : \DateTime
    {
        return $this->shootTime;
    }
    
    /**
     * @param \DateTime $shootTime
     */
    public function setShootTime(\DateTime $shootTime)
    {
        $this->shootTime = $shootTime;
    }
    
    /**
     * @return array
     */
    public function getPathTime() : array
    {
        return (array)$this->pathTime;
    }
    
    /**
     * @internal param array $pathTime
     */
    public function setPathTime()
    {
        foreach ($this->getPath() as $path) {
            $timeStrMicroseconds = $this->getShootTime()->format('u') + self::BULLET_DELAY_REAL_MICROSECONDS;
            $this->pathTime[$path] = new \DateTime($this->getShootTime()->format(DateTimeUser::DATE_TIME . '.' .$timeStrMicroseconds));
        }
    }
    
    public function move()
    {
//        var_dump('BulletsMove');
        $direction = $this->getDirection();
        if($direction === Canvas::CODE_LEFT_ARROW) {
            $this->x -= Bullet::BULLET_STEP;
        }
        if($direction === Canvas::CODE_UP_ARROW) {
            $this->y -= Bullet::BULLET_STEP;
        }
        if($direction === Canvas::CODE_RIGHT_ARROW) {
            $this->x += Bullet::BULLET_STEP;
        }
        if($direction === Canvas::CODE_DOWN_ARROW) {
            $this->y += Bullet::BULLET_STEP;
        }
//        var_dump($this->x);
    }
}