<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Star\Component\State\Builder\StateBuilder;
use Star\Component\State\StateMetadata;

final class PlayerState extends StateMetadata
{
    const T_START_ACTION = 'start-action';
    const T_PERFORM_ACTION = 'perform-action';
    const T_END_TURN = 'end-turn';

    const A_PLAYING = 'playing';

    const S_WAITING = 'waiting';
    const S_SELECTING = 'selecting';
    const S_DONE = 'done';

    public function __construct()
    {
        parent::__construct(self::S_WAITING);
    }

    /**
     * @return bool
     */
    public function isPlaying(): bool
    {
        return $this->hasAttribute(self::A_PLAYING);
    }

    /**
     * @return bool
     */
    public function hasPlayed(): bool
    {
        return $this->isInState(self::S_DONE);
    }

    /**
     * @return PlayerState
     */
    public function startAction(): self
    {
        return $this->transit(self::T_START_ACTION, 'player');
    }

    /**
     * @param UserAction $action
     *
     * @return PlayerState
     */
    public function performAction(UserAction $action): self
    {
        return $this->transit(self::T_PERFORM_ACTION, 'player');
    }

    public function endTurn()
    {
        return $this->transit(self::T_END_TURN, 'player');
    }

    /**
     * Returns the state workflow configuration.
     *
     * @param StateBuilder $builder
     *
     * +-------------------------------------------------+
     * |                   Transitions                   |
     * +-----------+---------+----------------+----------+
     * | From / to | waiting | selecting      | done     |
     * |=================================================|
     * | waiting   |   N/A   | start-action   | N/A      |
     * +-----------+---------+----------------+----------+
     * | selecting |   N/A   | perform-action | end-turn |
     * +-----------+---------+----------------+----------+
     * | done      |   N/A   |     N/A        | N/A      |
     * +-----------+---------+----------------+----------+
     *
     * +---------------------------+
     * |         Attributes        |
     * +-----------+---------------+
     * | State     |   playing     |
     * |===========================|
     * | pending   |   false       |
     * +-----------+---------------+
     * | selecting |   true        |
     * +-----------+---------------+
     * | done      |   false       |
     * +-----------+---------------+
     */
    protected function configure(StateBuilder $builder)
    {
        $builder->allowTransition(self::T_START_ACTION, self::S_WAITING, self::S_SELECTING);
        $builder->allowTransition(self::T_PERFORM_ACTION, self::S_SELECTING, self::S_SELECTING);
        $builder->allowTransition(self::T_END_TURN, self::S_SELECTING, self::S_DONE);
        $builder->addAttribute(self::A_PLAYING, [self::S_SELECTING]);
    }
}
