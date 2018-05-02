<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use StarLord\Domain\Model\Exception\InvalidPlanetOwnerException;

interface WriteOnlyPlanet extends Card, ReadOnlyPlanet
{
    /**
     * @param PlayerId $owner
     * @param Colons $colons
     */
    public function colonize(PlayerId $owner, Colons $colons);

    /**
     * @param PlayerId $playerId
     * @throws InvalidPlanetOwnerException
     */
    public function mine(PlayerId $playerId);

    /**
     * @return bool
     */
    public function isColonized(): bool;

    /**
     * @param PlayerId $playerId
     *
     * @return bool
     */
    public function isColonizedBy(PlayerId $playerId): bool;

    /**
     * @param WriteOnlyPlayer $player
     */
    public function collectResources(WriteOnlyPlayer $player);
}
