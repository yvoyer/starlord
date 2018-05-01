<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\UserAction;
use StarLord\Domain\Model\UserActionStore;

final class MinePlanet implements UserAction
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
    public function actionName(): string
    {
        return UserActionStore::MINE_PLANET;
    }

    /**
     * Whether the action needs to be performed
     *
     * @return bool
     */
    public function requiresPerform(): bool
    {
        return true;
    }
}
