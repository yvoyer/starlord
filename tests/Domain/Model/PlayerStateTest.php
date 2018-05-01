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

    public function test_it_should_allow_to_start_turn_when_in_setup()
    {
        $this->assertFalse($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->startTurn();

        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());
    }
//
//    public function test_it_should_allow_to_start_game_when_in_done()
//    {
//        $this->player->startTurn();
//        $this->player->endTurn();
//        $this->assertFalse($this->player->isActive());
//        $this->assertTrue($this->player->turnIsDone());
//
//        $this->player->startGame();
//
//        $this->assertTrue($this->player->isActive());
//        $this->assertFalse($this->player->turnIsDone());
//    }

    public function test_it_should_allow_to_start_action_when_playing()
    {
        $this->player->startTurn();
        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->startAction(new InProgressGame());

        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());
    }

    public function test_it_should_allow_to_start_turn_when_turn_completed()
    {
        $this->player->startTurn();
        $this->player->endTurn();
        $this->assertFalse($this->player->isActive());
        $this->assertTrue($this->player->turnIsDone());

        $this->player->startTurn();

        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());
    }

    public function test_it_should_allow_to_end_turn_when_playing()
    {
        $this->player->startTurn();
        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->endTurn();

        $this->assertFalse($this->player->isActive());
        $this->assertTrue($this->player->turnIsDone());
    }

    public function test_it_should_be_playing_when_performed_all_actions()
    {
        $this->player->startTurn();
        $this->player->startAction(new InProgressGame(), ['action']);
        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->performAction(new StringAction('action'));

        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());
    }

    public function test_it_should_remain_in_selecting_mode_when_actions_remains_on_perform()
    {
        $this->player->startTurn();
        $this->player->startAction(new InProgressGame(), ['action1', 'action2']);
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

        $this->player->startAction(new SettingUpGame(), ['action']);
        $this->player->performAction(new StringAction('action'));

        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());
    }
//
//    public function test_it_should_allow_to_start_game_when_all_actions_played()
//    {
//        $this->assertFalse($this->player->isActive());
//        $this->assertFalse($this->player->turnIsDone());
//
//        $this->player->startAction(new SettingUpGame(), ['action']);
//        $this->player->performAction(new StringAction('action'));
//        $this->assertTrue($this->player->actionsAreCompleted());
//        $this->player->startGame();
//
//        $this->assertTrue($this->player->isActive());
//        $this->assertFalse($this->player->turnIsDone());
//    }

    public function test_it_should_allow_to_start_action_on_setup()
    {
        $this->assertFalse($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->startAction(new SettingUpGame(), ['action']);

        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());
    }

    public function test_it_should_allow_to_perform_all_action_on_setup()
    {
        $this->assertFalse($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->startAction(new SettingUpGame(), ['action']);
        $this->player->performAction(new StringAction('action'));
        $this->assertTrue($this->player->actionsAreCompleted());

        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());
    }

    public function test_it_should_allow_to_perform_some_actions_on_setup()
    {
        $this->assertFalse($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());

        $this->player->startAction(new SettingUpGame(), ['action1', 'action2']);
        $this->player->performAction(new StringAction('action1'));

        $this->assertTrue($this->player->isActive());
        $this->assertFalse($this->player->turnIsDone());
    }
}
