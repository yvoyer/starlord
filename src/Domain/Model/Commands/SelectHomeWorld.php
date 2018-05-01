<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\UserAction;
use StarLord\Domain\Model\UserActionStore;

final class SelectHomeWorld implements UserAction
{
    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @var PlanetId
     */
    private $homeWorldId;

    /**
     * @param PlayerId $playerId
     * @param PlanetId $homeWorldId
     */
    public function __construct(PlayerId $playerId, PlanetId $homeWorldId)
    {
        $this->playerId = $playerId;
        $this->homeWorldId = $homeWorldId;
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
    public function homeWorldId(): PlanetId
    {
        return $this->homeWorldId;
    }

    /**
     * @return string
     */
    public function actionName(): string
    {
        return UserActionStore::SELECT_HOME_WORLD;
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
