<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface WriteOnlyShip
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
}
