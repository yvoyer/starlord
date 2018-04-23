<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Setup;

use StarLord\Domain\Events\GameWasCreated;
use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\Publisher;
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
     * @var Publisher
     */
    private $publisher;

    /**
     * @param WriteOnlyPlayers $players
     * @param Publisher $publisher
     */
    public function __construct(WriteOnlyPlayers $players, Publisher $publisher)
    {
        $this->players = $players;
        $this->publisher = $publisher;
    }

    /**
     * @param GameWasCreated $event
     */
    public function onGameWasCreated(GameWasCreated $event)
    {
        foreach ($event->players() as $playerId) {
            if ($this->players->playerExists($playerId)) {
                throw new \InvalidArgumentException("Player with id '{$playerId->toString()}' already exists.");
            }
            $player = TestPlayer::fromInt($playerId->toInt());
            $player->startAction([UserActionStore::SELECT_HOME_WORLD]);
            $this->players->savePlayer($playerId, $player);

            $this->publisher->publish(new PlayerJoinedGame($playerId));
        }
    }
}
