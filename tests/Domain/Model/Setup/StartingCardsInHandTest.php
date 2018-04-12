<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Setup;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\Card;
use StarLord\Domain\Model\Cards\AlwaysReturnCard;
use StarLord\Domain\Model\Cards\NotFoundCard;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class StartingCardsInHandTest extends TestCase
{
    /**
     * @var PlayerCollection
     */
    private $players;

    public function setUp()
    {
        $this->players = new PlayerCollection();
    }

    public function test_it_should_distribute_the_starting_cards_when_joining_the_game()
    {
        $playerId = 12;
        $this->players->savePlayer($playerId, $player = new TestPlayer($playerId));
        $cardId = 34;
        $handler = new StartingCardsInHand(
            $this->players,
            new AlwaysReturnCard($cardId, $this->createMock(Card::class)),
            1
        );

        $this->assertFalse($player->hasCardInHand($cardId));
        $this->assertAttributeCount(0, 'hand', $player);

        $handler->onPlayerJoinedGame(new PlayerJoinedGame($playerId));

        $this->assertTrue($player->hasCardInHand($cardId));
        $this->assertAttributeCount(1, 'hand', $player);
    }

    public function test_it_should_throw_exception_when_card_not_in_deck()
    {
        $playerId = 12;
        $this->players->savePlayer($playerId, $player = new TestPlayer($playerId));
        $handler = new StartingCardsInHand(
            $this->players,
            new AlwaysReturnCard(11, new NotFoundCard(11)),
            1
        );

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Card with id "11" was not found in deck.');
        $handler->onPlayerJoinedGame(new PlayerJoinedGame($playerId));
    }
}
