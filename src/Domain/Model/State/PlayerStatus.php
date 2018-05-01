<?php declare(strict_types=1);

namespace StarLord\Domain\Model\State;

use Star\Component\State\InvalidStateTransitionException;
use StarLord\Domain\Model\GameContext;
use StarLord\Domain\Model\ReadOnlyPlayer;

abstract class PlayerStatus
{
    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function turnIsDone(): bool
    {
        return false;
    }

    public function startAction(GameContext $context): PlayerStatus
    {
        throw InvalidStateTransitionException::notAllowedTransition(
            'start-action', 'player', $this->toString()
        );
    }

    public function performAction(ReadOnlyPlayer $player): PlayerStatus
    {
        throw InvalidStateTransitionException::notAllowedTransition(
            'start-action', 'player', $this->toString()
        );
    }

    public function endTurn(): PlayerStatus
    {
        throw InvalidStateTransitionException::notAllowedTransition(
            'end-turn', 'player', $this->toString()
        );
    }

    public function startTurn(): PlayerStatus
    {
        throw InvalidStateTransitionException::notAllowedTransition(
            'start-turn', 'player', $this->toString()
        );
    }

    protected abstract function toString(): string;

    /**
     * @param string $state
     *
     * @return PlayerStatus
     */
    public static function fromString(string $state): self
    {
        $class = __NAMESPACE__ . '\\' . $state . 'Player';

        return new $class();
    }
}
