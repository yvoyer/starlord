<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface ReadOnlyShip
{
    /**
     * @param PlanetId $planetId
     *
     * @return bool
     */
    public function isDocked(PlanetId $planetId): bool;
}
