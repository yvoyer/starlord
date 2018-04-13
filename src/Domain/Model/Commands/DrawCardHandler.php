<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use StarLord\Domain\Events\CardWasPlayed;
use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\Deck;
use StarLord\Domain\Model\WriteOnlyPlayers;

final class DrawCardHandler
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
     * @param CardWasPlayed $event
     */
    public function onCardWasPlayed(CardWasPlayed $event)
    {
        $this->__invoke(new DrawCard($event->playerId()));
    }

    /**
     * @param PlayerJoinedGame $event
     */
    public function onPlayerJoinedGame(PlayerJoinedGame $event)
    {
        for ($i = 0; $i < $this->countAtStart; $i ++) {
            $this->__invoke(new DrawCard($event->playerId()));
        }
    }

    /**
     * @param DrawCard $command
     */
    public function __invoke(DrawCard $command)
    {
        $player = $this->players->getPlayerWithId($command->playerId());
        $cardId = $this->deck->revealTopCard();
        $card = $this->deck->drawCard($cardId);
        $player->drawCard($cardId, $card);

        $this->players->savePlayer($command->playerId(), $player);
    }
}
