<?php declare(strict_types=1);

namespace StarLord\Domain\Model\State;

final class SetupPlayer extends PlayerStatus
{
    public function startTurn(): PlayerStatus
    {
        return new PlayingPlayer();
    }

    protected function toString(): string
    {
        return 'setup';
    }
}
