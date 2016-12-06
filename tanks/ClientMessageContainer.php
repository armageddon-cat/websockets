<?php
declare(strict_types=1);
namespace Tanks;

use Exceptions\EmptyPayLoadException;
use Exceptions\EmptyValueException;
use Exceptions\InvalidGuidException;
use Exceptions\TankNotExistsException;
use Exceptions\JsonDecodingException;
use Validators\GuidValidator;

/**
 * Class ClientMessageContainer
 * @package Tanks
 */
class ClientMessageContainer
{
    private $id;
    private $type;
    private $newd;
    private $time;
    private $payLoad;
    const TYPE_BULLET = 'bullet';
    
    /**
     * ClientMessageContainer constructor.
     *
     * @param array $decodedMessage
     *
     * @throws \Exceptions\EmptyValueException
     * @throws \Exceptions\InvalidGuidException
     * @throws \Exceptions\TankNotExistsException
     * @throws \Exceptions\JsonDecodingException
     * @throws \Exceptions\EmptyPayLoadException
     */
    public function __construct(array $decodedMessage)
    {
        $this->setPayLoad($decodedMessage);
        $payLoadObject = $this->jsonDecodePayLoad($this->getPayLoad());
        $this->setId($payLoadObject);
        $this->setType($payLoadObject);
        $this->setNewd($payLoadObject);
        $this->setTime($payLoadObject);
    }
    
    /**
     * @return string
     */
    public function getId(): string
    {
        return (string)$this->id;
    }
    
    /**
     * @param \stdClass $payLoadObject
     *
     * @throws \Exceptions\EmptyValueException
     * @throws \Exceptions\InvalidGuidException
     * @throws \Exceptions\TankNotExistsException
     */
    protected function setId(\stdClass $payLoadObject): void
    {
        if (empty($payLoadObject->id)) {
            throw new EmptyValueException('tank id');
        }
        if (!GuidValidator::validate($payLoadObject->id)) {
            throw new InvalidGuidException();
        }
        if (!TankRegistry::exists($payLoadObject->id)) {
            throw new TankNotExistsException();
        }
        $this->id = (string)$payLoadObject->id;
    }
    
    /**
     * @return string
     */
    public function getType(): string
    {
        return (string)$this->type;
    }
    
    /**
     * @param \stdClass $payLoadObject
     *
     * @internal param string $type
     * @throws \Exceptions\EmptyValueException
     */
    protected function setType(\stdClass $payLoadObject): void
    {
        $this->type = $payLoadObject->type ?? '';
    }
    
    /**
     * @return int
     */
    public function getNewd(): int
    {
        return (int)$this->newd;
    }
    
    /**
     * @param \stdClass $payLoadObject
     *
     * @internal param int $newd
     */
    protected function setNewd(\stdClass $payLoadObject): void
    {
        $this->newd = $payLoadObject->newd ?? 0;
    }
    
    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }
    
    /**
     * @param \stdClass $payLoadObject
     *
     * @internal param \stdClass $time
     */
    protected function setTime(\stdClass $payLoadObject): void
    {
        $this->time = null;
        if (isset($payLoadObject->time)) {
            $this->time = \DateTime::createFromFormat(DateTimeUser::UNIX_TIMESTAMP_MICROSECONDS, str_replace(',', '.', $payLoadObject->time/1000));
        }
    }
    
    /**
     * @return string
     */
    protected function getPayLoad(): string
    {
        return $this->payLoad;
    }
    
    /**
     * @param array $decodedMessage
     *
     * @throws \Exceptions\EmptyPayLoadException
     */
    protected function setPayLoad(array $decodedMessage): void
    {
        if (empty($decodedMessage['payload'])) {
            throw new EmptyPayLoadException();
        }
        $this->payLoad = $decodedMessage['payload'];
    }
    
    /**
     * @param string $getPayLoad
     *
     * @return \stdClass
     * @throws \Exceptions\JsonDecodingException
     */
    protected function jsonDecodePayLoad(string $getPayLoad): \stdClass
    {
        $decodedPayLoad = json_decode($getPayLoad);
        if ($decodedPayLoad === null) {
            throw new JsonDecodingException(json_last_error());
        }
        return $decodedPayLoad;
    }
}
