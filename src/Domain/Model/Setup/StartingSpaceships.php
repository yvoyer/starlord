<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Setup;

use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class StartingSpaceships
{
    /**
     * @var WriteOnlyPlayers
     */
    private $players;

    /**
     * @var int
     */
    private $defaultTransports;

    /**
     * @var int
     */
    private $defaultFighters;

    /**
     * @var int
     */
    private $defaultCruisers;

    /**
     * @param WriteOnlyPlayers $players
     * @param int $defaultTransports
     * @param int $defaultFighters
     * @param int $defaultCruisers
     */
    public function __construct(
        WriteOnlyPlayers $players,
        int $defaultTransports,
        int $defaultFighters,
        int $defaultCruisers
    ) {
        $this->players = $players;
        $this->defaultTransports = $defaultTransports;
        $this->defaultFighters = $defaultFighters;
        $this->defaultCruisers = $defaultCruisers;
    }

    /**
     * @param PlayerJoinedGame $event
     */
    public function onPlayerJoinedGame(PlayerJoinedGame $event)
    {
        $player = $this->players->getPlayerWithId($event->playerId());
        $player->addTransports($this->defaultTransports);
        $player->addFighters($this->defaultFighters);
        $player->addCruisers($this->defaultCruisers);

        $this->players->savePlayer($event->playerId(), $player);
    }
}
