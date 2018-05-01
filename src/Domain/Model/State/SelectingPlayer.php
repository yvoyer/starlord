<?php declare(strict_types=1);

namespace StarLord\Domain\Model\State;

use StarLord\Domain\Model\ReadOnlyPlayer;

final class SelectingPlayer extends PlayerStatus
{
    public function isActive(): bool
    {
        return true;
    }

    public function performAction(ReadOnlyPlayer $player): PlayerStatus
    {
        return new PlayingPlayer();
    }

    protected function toString(): string
    {
        return 'selecting';
    }
}
