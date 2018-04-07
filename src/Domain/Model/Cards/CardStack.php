<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Deck;
use Webmozart\Assert\Assert;

final class CardStack implements Deck
{
    /**
     * @var Card[]
     */
    private $cards = [];

    /**
     * @param Card[] $cards
     */
    public function __construct(array $cards)
    {
        Assert::allIsInstanceOf($cards, Card::class);
        $this->cards = $cards;
    }

    /**
     * Reveal the card on top of deck. Card should remain in deck.
     *
     * @return int The revealed card id
     */
    public function revealTopCard(): int
    {
        $ids = array_keys($this->cards);
        $id = array_pop($ids);
        if (! is_int($id)) {
            throw new \LogicException('No more card available in deck.');
        }

        return $id;
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
        $card = new NotFoundCard($cardId);
        if (isset($this->cards[$cardId])) {
            $card = $this->cards[$cardId];
        }
        unset($this->cards[$cardId]);

        return $card;
    }
}
