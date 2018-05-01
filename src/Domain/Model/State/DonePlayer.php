<?php declare(strict_types=1);

namespace StarLord\Domain\Model\State;

final class DonePlayer extends PlayerStatus
{
    public function turnIsDone(): bool
    {
        return true;
    }

    public function startTurn(): PlayerStatus
    {
        return new PlayingPlayer();
    }

    protected function toString(): string
    {
        return 'done';
    }
}
