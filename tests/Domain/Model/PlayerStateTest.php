<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Model\Exception\NotCompletedActionException;
use StarLord\Infrastructure\Model\Testing\StringAction;

final class PlayerStateTest extends TestCase
{
    /**
     * @var TestPlayer
     */
    private $player;

    public function setUp()
    {
        $this->player = TestPlayer::fromInt(1);
    }

    public function test_it_should_allow_to_start_game_when_in_setup()
    {
        $this->assertFalse($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->startGame();

        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());
    }

    public function test_it_should_allow_to_start_action_when_playing()
    {
        $this->player->startGame();
        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->startAction();

        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());
    }

    public function test_it_should_allow_to_start_turn_when_turn_completed()
    {
        $this->player->startGame();
        $this->player->endTurn();
        $this->assertFalse($this->player->isActive());
        $this->assertTrue($this->player->turnIsDone());

        $this->player->startTurn();

        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());
    }

    public function test_it_should_allow_to_end_turn_when_playing()
    {
        $this->player->startGame();
        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->endTurn();

        $this->assertFalse($this->player->isActive());
        $this->assertTrue($this->player->turnIsDone());
    }

    public function test_it_should_end_turn_when_performing_all_actions()
    {
        $this->player->startGame();
        $this->player->startAction(['action']);
        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->performAction(new StringAction('action'));

        $this->assertFalse($this->player->isActive());
        $this->assertTrue($this->player->turnIsDone());
    }

    public function test_it_should_remain_in_selecting_mode_when_actions_remains_on_perform()
    {
        $this->player->startGame();
        $this->player->startAction(['action1', 'action2']);
        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->performAction(new StringAction('action1'));

        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());
    }

    public function test_it_should_allow_to_play_action_when_in_setup()
    {
        $this->assertFalse($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->startAction(['action']);
        $this->player->performAction(new StringAction('action'));

        $this->assertFalse($this->player->isActive());
        $this->assertTrue($this->player->turnIsDone());
    }

    public function test_it_should_not_allow_to_start_game_when_remaining_actions()
    {
        $this->assertFalse($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->startAction(['action1', 'action2']);
        $this->expectException(NotCompletedActionException::class);
        $this->expectExceptionMessage(
            'Game cannot be started when player have some not completed actions "["action1","action2"]".'
        );
        $this->player->startGame();
    }

    public function test_it_should_allow_to_start_game_when_all_actions_played()
    {
        $this->assertFalse($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->startAction(['action']);
        $this->player->performAction(new StringAction('action'));
        $this->player->startGame();

        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());
    }
}
