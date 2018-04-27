<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Star\Component\State\Builder\StateBuilder;
use Star\Component\State\StateMetadata;

final class PlayerState extends StateMetadata
{
    const T_START_GAME = 'start-game';
    const T_START_ACTION = 'start-action';
    const T_PERFORM_ACTION = 'perform-action';
    const T_END_TURN = 'end-turn';
    const T_START_TURN = 'start-turn';

    const A_ACTIVE = 'is_active';

    const S_SETUP = 'setup';
    const S_PLAYING = 'playing';
    const S_SELECTING = 'selecting';
    const S_DONE = 'done';

    public function __construct()
    {
        parent::__construct(self::S_SETUP);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->hasAttribute(self::A_ACTIVE);
    }

    /**
     * @return bool
     */
    public function turnIsDone(): bool
    {
        return $this->isInState(self::S_DONE);
    }

    /**
     * @return PlayerState
     */
    public function startGame(): self
    {
        return $this->transit(self::T_START_GAME, 'player');
    }

    /**
     * @return PlayerState
     */
    public function startAction(): self
    {
        return $this->transit(self::T_START_ACTION, 'player');
    }

    /**
     * @return PlayerState
     */
    public function performAction(): self
    {
        return $this->transit(self::T_PERFORM_ACTION, 'player');
    }

    public function endTurn(): self
    {
        return $this->transit(self::T_END_TURN, 'player');
    }

    public function startTurn(): self
    {
        return $this->transit(self::T_START_TURN, 'player');
    }

    /**
     * Returns the state workflow configuration.
     *
     * @param StateBuilder $builder
     *
     * +------------------------------------------------------------------------------+
     * |                                    Transitions                               |
     * +-----------+--------------+------------+------------------+-------------------+
     * | From / to |    setup     |   playing  |     selecting    |       done        |
     * |==============================================================================|
     * | setup     |      N/A     | start-game | start-action     |  N/A              |
     * +-----------+--------------+------------+------------------+-------------------+
     * | playing   |      N/A     |     N/A    | start-action     | end-turn          |
     * +-----------+--------------+------------+------------------+-------------------+
     * | selecting |      N/A     |     N/A    | perform-action I | perform-action II |
     * +-----------+--------------+------------+------------------+-------------------+
     * | done      |      N/A     | start-turn |        N/A       |        N/A        |
     * |           |              | start-game |                  |                   |
     * +-----------+--------------+------------+------------------+-------------------+
     *
     * Note:
     *   I: When actions remains
     *   II: When no actions remains
     *
     * +---------------------------+
     * |         Attributes        |
     * +-----------+---------------+
     * | State     |   is_active   |
     * |===========================|
     * | setup     |   false       |
     * +-----------+---------------+
     * | playing   |   true        |
     * +-----------+---------------+
     * | selecting |   true        |
     * +-----------+---------------+
     * | done      |   false       |
     * +-----------+---------------+
     */
    protected function configure(StateBuilder $builder)
    {
        $builder->allowTransition(
            self::T_START_GAME, [self::S_SETUP, self::S_DONE], self::S_PLAYING
        );
        $builder->allowTransition(
            self::T_START_ACTION, [self::S_PLAYING, self::S_SETUP], self::S_SELECTING
        );
        $builder->allowTransition(
            self::T_PERFORM_ACTION, self::S_SELECTING, self::S_SELECTING
        );
        $builder->allowTransition(
            self::T_END_TURN, [self::S_PLAYING, self::S_SELECTING], self::S_DONE
        );
        $builder->allowTransition(
            self::T_START_TURN, self::S_DONE, self::S_PLAYING
        );
        $builder->addAttribute(
            self::A_ACTIVE, [self::S_PLAYING, self::S_SELECTING]
        );
    }
}
