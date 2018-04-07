<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Setup;

use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\Deck;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class StartingCardsInHand
{
    /**
     * @var WriteOnlyPlayers
     */
    private $players;

    /**
     * @var Deck
     */
    private $deck;

    /**
     * @var int
     */
    private $countAtStart;

    /**
     * @param WriteOnlyPlayers $players
     * @param Deck $deck
     * @param int $countAtStart
     */
    public function __construct(WriteOnlyPlayers $players, Deck $deck, int $countAtStart)
    {
        $this->players = $players;
        $this->deck = $deck;
        $this->countAtStart = $countAtStart;
    }

    /**
     * @param PlayerJoinedGame $event
     */
    public function onPlayerJoinedGame(PlayerJoinedGame $event)
    {
        $player = $this->players->getPlayerWithId($event->playerId());

        for ($i = 0; $i < $this->countAtStart; $i ++) {
            $card = $this->deck->drawCard($cardId = $this->deck->revealTopCard());
            $player->drawCard($cardId, $card);
        }

        $this->players->savePlayer($event->playerId(), $player);
    }
}
