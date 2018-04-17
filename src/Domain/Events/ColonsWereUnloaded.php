<?php declare(strict_types=1);

namespace StarLord\Domain\Events;

use StarLord\Domain\Model\Colons;
use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\ShipId;

final class ColonsWereUnloaded implements StarLordEvent
{
    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @var PlanetId
     */
    private $planetId;

    /**
     * @var Colons
     */
    private $quantity;

    /**
     * @var ShipId
     */
    private $shipId;

    /**
     * @param PlayerId $playerId
     * @param PlanetId $planetId
     * @param Colons $quantity
     * @param ShipId $shipId
     */
    public function __construct(
        PlayerId $playerId,
        PlanetId $planetId,
        Colons $quantity,
        ShipId $shipId
    ) {
        $this->playerId = $playerId;
        $this->planetId = $planetId;
        $this->quantity = $quantity;
        $this->shipId = $shipId;
    }

    /**
     * @return PlayerId
     */
    public function playerId(): PlayerId
    {
        return $this->playerId;
    }

    /**
     * @return PlanetId
     */
    public function planetId(): PlanetId
    {
        return $this->planetId;
    }

    /**
     * @return Colons
     */
    public function quantity(): Colons
    {
        return $this->quantity;
    }

    /**
     * @return ShipId
     */
    public function shipId(): ShipId
    {
        return $this->shipId;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
