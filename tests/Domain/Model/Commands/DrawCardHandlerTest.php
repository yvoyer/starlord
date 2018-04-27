<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\CardWasPlayed;
use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Cards\AlwaysReturnCard;
use StarLord\Domain\Model\Cards\NotFoundCard;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class DrawCardHandlerTest extends TestCase
{
    /**
     * @var DrawCardHandler
     */
    private $handler;

    /**
     * @var PlayerCollection
     */
    private $players;

    /**
     * @var AlwaysReturnCard
     */
    private $deck;

    /**
     * @var TestPlayer
     */
    private $player;

    public function setUp()
    {
        $this->player = TestPlayer::playingPlayer(1);
        $this->handler = new DrawCardHandler(
            $this->players = new PlayerCollection(),
            $this->deck = new AlwaysReturnCard(10, $this->createMock(Card::class)),
            1
        );
    }

    public function test_it_should_draw_card_when_card_is_played()
    {
        $this->players->savePlayer(new PlayerId(1), $this->player);
        $this->assertCount(0, $this->player->cards());

        $this->handler->onCardWasPlayed(new CardWasPlayed(10, new PlayerId(1)));

        $this->assertCount(1, $this->player->cards());
    }

    public function test_it_should_distribute_the_starting_cards_when_joining_the_game()
    {
        $this->players->savePlayer(new PlayerId(1), $this->player);
        $cardId = 10;

        $this->assertFalse($this->player->hasCardInHand($cardId));
        $this->assertAttributeCount(0, 'hand', $this->player);

        $this->handler->onPlayerJoinedGame(PlayerJoinedGame::fromInt(1));

        $this->assertTrue($this->player->hasCardInHand($cardId));
        $this->assertAttributeCount(1, 'hand', $this->player);
    }

    public function test_it_should_throw_exception_when_card_not_in_deck()
    {
        $playerId = new PlayerId(12);
        $this->players->savePlayer($playerId, $this->player);
        $handler = new DrawCardHandler(
            $this->players,
            new AlwaysReturnCard(11, new NotFoundCard(11)),
            1
        );

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Card with id "11" was not found in deck.');
        $handler->onPlayerJoinedGame(new PlayerJoinedGame($playerId));
    }
}
