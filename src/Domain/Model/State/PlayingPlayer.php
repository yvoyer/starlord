<?php declare(strict_types=1);

namespace StarLord\Domain\Model\State;

use StarLord\Domain\Model\GameContext;

final class PlayingPlayer extends PlayerStatus
{
    public function isActive(): bool
    {
        return true;
    }

    public function startTurn(): PlayerStatus
    {
        return $this;
    }

    public function endTurn(): PlayerStatus
    {
        return new DonePlayer();
    }

    public function startAction(GameContext $context): PlayerStatus
    {
        return new SelectingPlayer();
    }

    protected function toString(): string
    {
        return 'playing';
    }
}
