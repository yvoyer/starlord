<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use StarLord\Domain\Events\CardWasPlayed;
use StarLord\Domain\Model\Deck;

final class DeckManager
{
    /**
     * @var Deck
     */
    private $deck;

    /**
     * @param Deck $deck
     */
    public function __construct(Deck $deck)
    {
        $this->deck = $deck;
    }

    /**
     * @param CardWasPlayed $event
     */
    public function onCardWasPlayed(CardWasPlayed $event)
    {
//        $this->deck->discardCard($event->cardId());
    }
}
