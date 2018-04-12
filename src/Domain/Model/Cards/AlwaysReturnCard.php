<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Deck;

final class AlwaysReturnCard implements Deck
{
    /**
     * @var int
     */
    private $cardId;

    /**
     * @var Card
     */
    private $card;

    /**
     * @param int $cardId
     * @param Card $card
     */
    public function __construct($cardId, Card $card)
    {
        $this->cardId = $cardId;
        $this->card = $card;
    }

    /**
     * Reveal the card on top of deck. Card should remain in deck.
     *
     * @return int The revealed card id
     */
    public function revealTopCard(): int
    {
        return $this->cardId;
    }

    /**
     * Remove the card from the deck
     *
     * @param int $cardId
     *
     * @return Card
     */
    public function drawCard(int $cardId): Card
    {
        return $this->card;
    }
}
