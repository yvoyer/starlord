<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface Deck
{
    /**
     * Reveal the card on top of deck. Card should remain in deck.
     *
     * @return int The revealed card id
     */
    public function revealTopCard(): int;

    /**
     * Remove the card from the deck
     *
     * @param int $cardId
     *
     * @return Card
     */
    public function drawCard(int $cardId): Card;
}
