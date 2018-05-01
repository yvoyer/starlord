<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\PlayerTurnHasEnded;
use StarLord\Domain\Model\InProgressGame;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Infrastructure\Model\Testing\StringAction;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class EndPlayerTurnHandlerTest extends TestCase
{
    /**
     * @var EndPlayerTurnHandler
     */
    private $handler;

    /**
     * @var TestPlayer
     */
    private $player;

    /**
     * @var MockObject
     */
    private $publisher;

    public function setUp()
    {
        $this->handler = new EndPlayerTurnHandler(
            new PlayerCollection([1 => $this->player = TestPlayer::playingPlayer(1)]),
            $this->publisher = $this->createMock(Publisher::class)
        );
    }

    public function test_it_should_end_the_player_turn_when_action_was_performed()
    {
        $this->assertEmpty($this->player->actionsToPerform());
        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->handler->__invoke(new EndPlayerTurn(new PlayerId(1)));

        $this->assertFalse($this->player->isActive());
        $this->assertTrue($this->player->turnIsDone());
    }

    /**
     * @expectedException        \StarLord\Domain\Model\Exception\NotCompletedActionException
     * @expectedExceptionMessage Cannot end turn when remaining actions are required ["action"].
     */
    public function test_it_should_not_allow_to_end_turn_when_waiting_for_playing_action()
    {
        $this->player->startAction(new InProgressGame(), ['action']);
        $this->assertTrue($this->player->isActive());
        $this->assertCount(1, $this->player->actionsToPerform());
        $this->handler->__invoke(new EndPlayerTurn(new PlayerId(1)));
    }

    /**
     * @expectedException        \Star\Component\State\InvalidStateTransitionException
     * @expectedExceptionMessage The transition 'end-turn' is not allowed when context 'player' is in state 'done'.
     */
    public function test_it_should_not_allow_to_end_turn_when_turn_already_ended()
    {
        $this->player->startAction(new InProgressGame(), ['action']);
        $this->player->performAction(new StringAction('action'));
        $this->player->endTurn();
        $this->assertTrue($this->player->turnIsDone());

        $this->handler->__invoke(new EndPlayerTurn(new PlayerId(1)));
    }

    public function test_it_should_publish_event_when_ending_player_turn()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(PlayerTurnHasEnded::class));

        $this->handler->__invoke(new EndPlayerTurn(new PlayerId(1)));
    }
}
