<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use PHPUnit\Framework\TestCase;
use Star\Component\Identity\Exception\EntityNotFoundException;
use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\WriteOnlyPlayer;

final class CardStackTest extends TestCase
{
    /**
     * @var Card
     */
    private $card;

    public function setUp()
    {
        $this->card = $this->createMock(Card::class);
    }

    public function test_it_should_reveal_top_of_card()
    {
        $stack = new CardStack([1 => $this->card]);
        $this->assertAttributeCount(1, 'cards', $stack);
        $this->assertSame(1, $stack->revealTopCard());
        $this->assertAttributeCount(1, 'cards', $stack);
    }

    public function test_it_should_remove_card_from_card()
    {
        $stack = new CardStack([1 => $this->card]);
        $this->assertAttributeCount(1, 'cards', $stack);
        $this->assertInstanceOf(Card::class, $stack->drawCard(1));
        $this->assertAttributeCount(0, 'cards', $stack);
    }

    public function test_it_should_throw_exception_when_deck_is_empty()
    {
        $stack = new CardStack([]);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('No more card available in deck.');
        $stack->revealTopCard();
    }

    public function test_it_should_throw_exception_when_card_not_found()
    {
        $stack = new CardStack([]);
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage("Object of class 'StarLord\Domain\Model\Card' with id '1' could not be found.");

        $stack->drawCard(1);
    }
}
