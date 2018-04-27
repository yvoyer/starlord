<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\CardWasPlayed;
use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Cards\AlwaysReturnCard;
use StarLord\Domain\Model\Exception\InvalidCardException;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class PlayCardHandlerTest extends TestCase
{
    /**
     * @var PlayerCollection
     */
    private $players;

    /**
     * @var MockObject|Publisher
     */
    private $publisher;

    /**
     * @var PlayCardHandler
     */
    private $handler;

    /**
     * @var TestPlayer
     */
    private $player;

    /**
     * @var Card|MockObject
     */
    private $card;

    public function setUp()
    {
        $this->player = TestPlayer::playingPlayer(12);
        $this->players = new PlayerCollection([$this->player]);
        $this->publisher = $this->createMock(Publisher::class);
        $this->handler = new PlayCardHandler(
            $this->players,
            new AlwaysReturnCard(34, $this->card = $this->createMock(Card::class)),
            $this->publisher
        );
    }

    public function test_it_should_call_card_operation()
    {
        $this->player->drawCard(34, $this->card);
        $this->card
            ->expects($this->once())
            ->method('whenPlayedBy');

        $this->handler->__invoke(new PlayCard($this->player->getIdentity(), 34));
    }

    public function test_it_should_publish_event()
    {
        $this->player->drawCard(34, $this->card);
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(CardWasPlayed::class));

        $this->handler->__invoke(new PlayCard($this->player->getIdentity(), 34));
    }

    public function test_it_should_not_allow_to_play_card_when_not_in_hand_of_player()
    {
        $this->assertFalse($this->player->hasCardInHand($cardId = 34));

        $this->expectException(InvalidCardException::class);
        $this->expectExceptionMessage('The card "34" cannot be played since it is not in player "1" hand.');
        $this->handler->__invoke(new PlayCard($this->player->getIdentity(), $cardId));
    }
}
