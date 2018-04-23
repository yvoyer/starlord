<?php declare(strict_types=1);

namespace StarLord\Domain\Events;

use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\PlayerId;

final class HomeWorldWasSelected implements StarLordEvent
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
     * @param PlayerId $playerId
     * @param PlanetId $planetId
     */
    public function __construct(PlayerId $playerId, PlanetId $planetId)
    {
        $this->playerId = $playerId;
        $this->planetId = $planetId;
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
     * @return string
     */
    public function serialize()
    {
        return json_encode(
            [
                'name' => 'home_world_selected',
                'player' => $this->playerId->toString(),
                'planet' => $this->planetId->toString(),
            ]
        );
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
