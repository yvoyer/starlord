<?php declare(strict_types=1);

namespace StarLord\Domain\Events;

use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\ShipId;

final class ShipWasMoved implements StarLordEvent
{
    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @var ShipId
     */
    private $ship;

    /**
     * @var PlanetId
     */
    private $from;

    /**
     * @var PlanetId
     */
    private $to;

    /**
     * @param PlayerId $playerId
     * @param ShipId $ship
     * @param PlanetId $from
     * @param PlanetId $to
     */
    public function __construct(
        PlayerId $playerId,
        ShipId $ship,
        PlanetId $from,
        PlanetId $to
    ) {
        $this->playerId = $playerId;
        $this->ship = $ship;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return PlayerId
     */
    public function playerId(): PlayerId
    {
        return $this->playerId;
    }

    /**
     * @return ShipId
     */
    public function ship(): ShipId
    {
        return $this->ship;
    }

    /**
     * @return PlanetId
     */
    public function from(): PlanetId
    {
        return $this->from;
    }

    /**
     * @return PlanetId
     */
    public function to(): PlanetId
    {
        return $this->to;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
