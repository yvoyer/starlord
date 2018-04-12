<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Setup;

use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class StartingCredit
{
    /**
     * @var WriteOnlyPlayers
     */
    private $players;

    /**
     * @var int
     */
    private $startingCredit;

    /**
     * @param WriteOnlyPlayers $players
     * @param int $startingCredit
     */
    public function __construct(WriteOnlyPlayers $players, int $startingCredit)
    {
        $this->players = $players;
        $this->startingCredit = $startingCredit;
    }

    /**
     * @param PlayerJoinedGame $event
     */
    public function onPlayerJoinedGame(PlayerJoinedGame $event)
    {
        $player = $this->players->getPlayerWithId($event->playerId());
        $player->addCredit(new Credit($this->startingCredit));

        $this->players->savePlayer($event->playerId(), $player);
    }
}
