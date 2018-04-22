<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Model\Colons;
use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\PlayerId;

final class ColonizePlanet
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
    private $colons;

    /**
     * @param PlayerId $playerId
     * @param PlanetId $planetId
     * @param Colons $colons
     */
    public function __construct(PlayerId $playerId, PlanetId $planetId, Colons $colons)
    {
        $this->playerId = $playerId;
        $this->planetId = $planetId;
        $this->colons = $colons;
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
    public function colons(): Colons
    {
        return $this->colons;
    }
}
