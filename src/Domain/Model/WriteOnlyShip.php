<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface WriteOnlyShip extends ReadOnlyShip
{
    /**
     * @return ShipId
     */
    public function getIdentity(): ShipId;

    /**
     * @param PlanetId $planetId
     */
    public function moveTo(PlanetId $planetId);

    /**
     * @param Colons $colons
     */
    public function loadColons(Colons $colons);

    /**
     * @param Colons $colons
     */
    public function unloadColons(Colons $colons);
}
