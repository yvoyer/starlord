<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface WriteOnlyPlanet extends Card, ReadOnlyPlanet
{
    /**
     * @param PlayerId $owner
     * @param Colons $colons
     */
    public function colonize(PlayerId $owner, Colons $colons);

    /**
     * @return bool
     */
    public function isColonized(): bool;

    /**
     * @param WriteOnlyPlayer $player
     */
    public function collectResources(WriteOnlyPlayer $player);
}
