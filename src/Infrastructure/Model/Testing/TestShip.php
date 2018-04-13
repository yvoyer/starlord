<?php declare(strict_types=1);

namespace StarLord\Infrastructure\Model\Testing;

use StarLord\Domain\Model\Colons;
use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\ReadOnlyShip;
use StarLord\Domain\Model\ShipId;
use StarLord\Domain\Model\WriteOnlyShip;

final class TestShip implements WriteOnlyShip, ReadOnlyShip
{
    /**
     * @var ShipId
     */
    private $shipId;

    /**
     * @var PlanetId
     */
    private $location;

    /**
     * @var Colons
     */
    private $colons;

    /**
     * @var int
     */
    private $capacity = 0;

    /**
     * @param ShipId $shipId
     * @param PlanetId $location
     */
    private function __construct(ShipId $shipId, PlanetId $location)
    {
        $this->shipId = $shipId;
        $this->location = $location;
        $this->colons = new Colons(0);
    }

    /**
     * @return ShipId
     */
    public function getIdentity(): ShipId
    {
        return $this->shipId;
    }

    /**
     * @param PlanetId $planetId
     */
    public function moveTo(PlanetId $planetId)
    {
        $this->location = $planetId;
    }

    /**
     * @param Colons $colons
     */
    public function loadColons(Colons $colons)
    {
        $this->colons = $this->colons->addColons($colons->toInt());
    }

    /**
     * @param PlanetId $planetId
     *
     * @return bool
     */
    public function isDocked(PlanetId $planetId): bool
    {
        return $this->location->toString() === $planetId->toString();
    }

    /**
     * @return int
     */
    public function maximumCapacity(): int
    {
        return $this->capacity;
    }

    /**
     * @return Colons
     */
    public function getColons(): Colons
    {
        return $this->colons;
    }

    /**
     * @param int $shipId
     * @param int $planetId
     *
     * @return TestShip
     */
    public static function fromInt(int $shipId, int $planetId): self
    {
        return new self(new ShipId($shipId), new PlanetId($planetId));
    }

    /**
     * @param int $shipId
     * @param int $planetId
     *
     * @return TestShip
     */
    public static function transport(int $shipId, int $planetId): self
    {
        $ship = self::fromInt($shipId, $planetId);
        $ship->capacity = 3;

        return $ship;
    }
}
