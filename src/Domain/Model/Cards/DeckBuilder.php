<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\Deck;
use StarLord\Domain\Model\WriteOnlyPlayer;

final class DeckBuilder
{
    /**
     * @var Card[]
     */
    private $cards = [];

    /**
     * @param int $cardId
     */
    public function addPendingCard(int $cardId)
    {
        $this->addCard($cardId, new class($cardId) implements Card {
            /**
             * @var int
             */
            private $cardId;

            /**
             * @param int $cardId
             */
            public function __construct(int $cardId)
            {
                $this->cardId = $cardId;
            }

            /**
             * @param WriteOnlyPlayer $player
             */
            public function whenPlayedBy(WriteOnlyPlayer $player)
            {
                throw new \RuntimeException("Card with id '{$this->cardId}' is not defined yet.");
            }

            /**
             * @param WriteOnlyPlayer $player
             */
            public function whenDraw(WriteOnlyPlayer $player)
            {
            }
        });
    }

    /**
     * @param int $cardId
     * @param int $quantity
     */
    public function minePlanet(int $cardId, int $quantity)
    {
        $this->addCard(
            $cardId,
            new MinePlanet($quantity)
        );
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
     * @param int $cardId
     */
    public function colonizePlanet(int $cardId)
    {
        $this->addCard($cardId, new ColonizePlanet(new Credit(2)));
    }

    /**
     * @return Deck
     */
    public function createDeck(): Deck
    {
        return new CardStack($this->cards);
    }

    /**
     * @return CardRegistry
     */
    public function createRegistry(): CardRegistry
    {
        return new CardStack($this->cards);
    }

    private function addCard(int $cardId, Card $card)
    {
        $this->cards[$cardId] = $card;
    }
}
