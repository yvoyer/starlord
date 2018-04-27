<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Setup;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class StartingCreditTest extends TestCase
{
    /**
     * @var PlayerCollection
     */
    private $players;

    public function setUp()
    {
        $this->players = new PlayerCollection();
    }

    public function test_it_should_give_players_their_base_credit()
    {
        $this->players->savePlayer(new PlayerId(1), $playerOne = TestPlayer::playingPlayer(1));
        $this->players->savePlayer(new PlayerId(2), $playerTwo = TestPlayer::playingPlayer(2));

        $this->assertSame(0, $playerOne->getCredit()->toInt());
        $this->assertSame(0, $playerTwo->getCredit()->toInt());

        $handler = new StartingCredit($this->players, 3);
        $handler->onPlayerJoinedGame(new PlayerJoinedGame(new PlayerId(1)));

        $this->assertSame(3, $playerOne->getCredit()->toInt());
        $this->assertSame(0, $playerTwo->getCredit()->toInt());
    }
}
