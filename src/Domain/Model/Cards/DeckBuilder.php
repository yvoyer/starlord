<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Deck;

final class DeckBuilder
{
    /**
     * @var Card[]
     */
    private $cards = [];

    /**
     * @param int $cardId
     * @param int $quantity
     * @param string $color
     * @param string $size
     */
    public function mineCrystal(int $cardId, int $quantity, string $color, string $size)
    {
        $this->addCard($cardId, MineCrystal::fromColor($quantity, $color, $size));
    }

    /**
     * @param int $cardId
     * @param int $quantity
     */
    public function buyTransport(int $cardId, int $quantity = 1)
    {
        $this->addCard($cardId, new BuildTransport($quantity));
    }

    /**
     * @param int $cardId
     */
    public function addCruiser(int $cardId)
    {
        $this->addCard($cardId, new BuildCruiser(1));
    }

    /**
     * @param int $cardId
     */
    public function addFighter(int $cardId)
    {
        $this->addCard($cardId, new BuildFighter(1));
    }

    /**
     * @param int $cardId
     */
    public function buildColonists(int $cardId)
    {
        $this->addCard($cardId, new BuildColonists(1));
    }

    /**
     * @return Deck
     */
    public function createDeck(): Deck
    {
        return new CardStack($this->cards);
    }

    private function addCard(int $cardId, Card $card)
    {
        $this->cards[$cardId] = $card;
    }
}
