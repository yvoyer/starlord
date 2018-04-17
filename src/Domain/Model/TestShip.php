<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use StarLord\Domain\Model\Exception\CapacityException;

final class TestShip implements WriteOnlyShip
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
    private $capacity;

    /**
     * @param ShipId $shipId
     * @param PlanetId $location
     * @param int $capacity
     */
    public function __construct(ShipId $shipId, PlanetId $location, int $capacity)
    {
        $this->shipId = $shipId;
        $this->location = $location;
        $this->colons = new Colons(0);
        $this->capacity = $capacity;
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
        if ($this->colons->addColons($colons->toInt())->greaterEqual($this->capacity)) {
            throw new CapacityException(
                sprintf(
                    'Ship do not allow to exceed of "%s", tried to load %s colons with actual capacity "%s".',
                    $this->capacity,
                    $colons->toInt(),
                    $this->colons->toInt() . '/' . $this->capacity
                )
            );
        }

        $this->colons = $this->colons->addColons($colons->toInt());
    }

    /**
     * @param Colons $colons
     */
    public function unloadColons(Colons $colons)
    {
        $this->colons = $this->colons->removeColons($colons->toInt());
    }

    /**
     * @return PlanetId
     */
    public function locationId(): PlanetId
    {
        return $this->location;
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
     * @return Colons
     */
    public function remainingCapacity(): Colons
    {
        $remaining = 0;
        if ($this->capacity >= $this->colons->toInt()) {
            $remaining = $this->capacity - $this->colons->toInt();
        }
        return new Colons($remaining);
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
     * @param int $capacity
     *
     * @return TestShip
     */
    public static function fromInt(int $shipId, int $planetId, int $capacity = 0): self
    {
        return new self(new ShipId($shipId), new PlanetId($planetId), $capacity);
    }

    /**
     * @param int $shipId
     * @param int $planetId
     *
     * @return TestShip
     */
    public static function transport(int $shipId, int $planetId): self
    {
        $ship = self::fromInt($shipId, $planetId, 3);

        return $ship;
    }

    /**
     * @param int $shipId
     * @param int $planetId
     *
     * @return TestShip
     */
    public static function cruiser(int $shipId, int $planetId): self
    {
        $ship = self::fromInt($shipId, $planetId, 1);

        return $ship;
    }

    /**
     * @param int $shipId
     * @param int $planetId
     *
     * @return TestShip
     */
    public static function fighter(int $shipId, int $planetId): self
    {
        return self::fromInt($shipId, $planetId);
    }
}
