<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Star\Component\State\InvalidStateTransitionException;
use StarLord\Domain\Events\PlayerTurnHasEnded;
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
            new PlayerCollection([1 => $this->player = TestPlayer::fromInt(1)]),
            $this->publisher = $this->createMock(Publisher::class)
        );
    }

    public function test_it_should_end_the_player_turn_when_action_was_performed()
    {
        $this->player->startAction(['action']);
        $this->player->performAction(new StringAction('action'));
        $this->assertTrue($this->player->isPlaying());
        $this->assertFalse($this->player->hasPlayed());

        $this->handler->__invoke(new EndPlayerTurn(new PlayerId(1)));

        $this->assertFalse($this->player->isPlaying());
        $this->assertTrue($this->player->hasPlayed());
    }

    /**
     * @expectedException        \Star\Component\State\InvalidStateTransitionException
     * @expectedExceptionMessage The transition 'end-turn' is not allowed when context 'player' is in state 'waiting'.
     */
    public function test_it_should_not_allow_to_end_turn_when_waiting_for_playing_action()
    {
        $this->assertFalse($this->player->isPlaying());
        $this->handler->__invoke(new EndPlayerTurn(new PlayerId(1)));
    }

    /**
     * @expectedException        \Star\Component\State\InvalidStateTransitionException
     * @expectedExceptionMessage The transition 'end-turn' is not allowed when context 'player' is in state 'done'.
     */
    public function test_it_should_not_allow_to_end_turn_when_turn_already_ended()
    {
        $this->player->startAction(['action']);
        $this->player->performAction(new StringAction('action'));
        $this->player->endTurn();
        $this->assertTrue($this->player->hasPlayed());

        $this->handler->__invoke(new EndPlayerTurn(new PlayerId(1)));
    }

    public function test_it_should_publish_even_when_ending_player_turn()
    {
        $this->player->startAction();
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(PlayerTurnHasEnded::class));

        $this->handler->__invoke(new EndPlayerTurn(new PlayerId(1)));
    }

    public function test_it_should_not_allow_to_end_player_turn_when_required_action_were_not_performed()
    {
        $this->assertFalse($this->player->isPlaying());
        $this->expectException(InvalidStateTransitionException::class);
        $this->expectExceptionMessage(
            "The transition 'end-turn' is not allowed when context 'player' is in state 'waiting'."
        );
        $this->handler->__invoke(new EndPlayerTurn(new PlayerId(1)));
    }
}
