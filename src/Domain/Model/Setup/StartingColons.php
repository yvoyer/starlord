<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Setup;

use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class StartingColons
{
    /**
     * @var WriteOnlyPlayers
     */
    private $players;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @param WriteOnlyPlayers $players
     * @param int $quantity
     */
    public function __construct(WriteOnlyPlayers $players, int $quantity)
    {
        $this->players = $players;
        $this->quantity = $quantity;
    }

    /**
     * @param PlayerJoinedGame $event
     */
    public function onPlayerJoinedGame(PlayerJoinedGame $event)
    {
        $player = $this->players->getPlayerWithId($event->playerId());
        $player->addColons($this->quantity);

        $this->players->savePlayer($event->playerId(), $player);
    }
}
