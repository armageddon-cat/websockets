<?php
declare(strict_types=1);

namespace Tanks;


class TankMoveRoute
{
    /**
     *  @param array $storage
     */
    private $storage = [];
    
    public function __construct(Tank $tank)
    {
        $this->storage[$tank->getTankCenterX().':'.$tank->getTankCenterY()] = new TankMove($tank);
    }
    
    /**
     * @param Tank $tank
     */
    public function addMove(Tank $tank)
    {
        $firstMove = $this->getFirst();
        $firstMoveTime = (float)$firstMove->getTime()->format(DateTimeUser::UNIX_TIMESTAMP_MICROSECONDS);
        $currentMoveTime = (float)$tank->getTime()->format(DateTimeUser::UNIX_TIMESTAMP_MICROSECONDS);
        $diff = $currentMoveTime - $firstMoveTime;
        if ($diff > Bullet::BULLET_FLY_TIME_SECONDS) {
            reset($this->storage);
            unset($this->storage[key($this->storage)]);
        }
        $this->storage[$tank->getTankCenterX().':'.$tank->getTankCenterY()] = new TankMove($tank);
    }
    
    /**
     * @return TankMove
     */
    public function getFirst() : TankMove
    {
        return reset($this->storage);
    }
    
    /**
     * @param $id
     *
     * @return TankMove
     */
    public function getMove(string $id) : TankMove
    {
        return $this->storage[$id];
    }
    
    /**
     * @param $id
     *
     * @return bool
     */
    public function checkMove(string $id) : bool
    {
        return isset($this->storage[$id]);
    }
    
    /**
     * @param $id
     */
    public function removeMove(string $id)
    {
        unset($this->storage[$id]);
    }
    
    /**
     * @return TankMove[]
     */
    public function getTankMoves() : array
    {
        return $this->storage;
    }
    
    /**
     *
     */
    public function unsetStorage()
    {
        $this->storage = [];
    }
}