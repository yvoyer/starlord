<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\GameHasEnded;
use StarLord\Domain\Events\TurnWasStarted;
use StarLord\Domain\Model\EndedGame;
use StarLord\Domain\Model\InProgressGame;
use StarLord\Domain\Model\Publisher;

final class EndTurnHandlerTest extends TestCase
{
    /**
     * @var EndTurnHandler
     */
    private $handler;

    /**
     * @var MockObject|Publisher
     */
    private $publisher;

    public function setUp()
    {
        $this->publisher = $this->createMock(Publisher::class);
    }

    public function test_it_should_end_the_game_at_end_of_turn()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(GameHasEnded::class));

        $handler = new EndTurnHandler(new EndedGame(), $this->publisher);
        $handler(new EndTurn());
    }

    public function test_it_should_start_a_new_turn_at_end_of_turn()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(TurnWasStarted::class));

        $handler = new EndTurnHandler(new InProgressGame(), $this->publisher);
        $handler(new EndTurn());
    }
}
