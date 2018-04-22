<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Setup;

use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Domain\Model\UserActionStore;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class PlayerSetup
{
    /**
     * @var WriteOnlyPlayers
     */
    private $players;

    /**
     * @param WriteOnlyPlayers $players
     */
    public function __construct(WriteOnlyPlayers $players)
    {
        $this->players = $players;
    }

    /**
     * @param PlayerJoinedGame $event
     */
    public function onPlayerJoinedGame(PlayerJoinedGame $event)
    {
        $playerId = $event->playerId();
        if ($this->players->playerExists($playerId)) {
            throw new \InvalidArgumentException("Player with id '{$playerId->toString()}' already exists.");
        }
        $player = TestPlayer::fromInt($playerId->toInt());
//todo        $player->startAction([UserActionStore::SELECT_HOME_WORLD]);

        $this->players->savePlayer($playerId, $player);
    }
}
