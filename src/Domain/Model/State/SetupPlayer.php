<?php declare(strict_types=1);

namespace StarLord\Domain\Model\State;

use StarLord\Domain\Model\GameContext;
use StarLord\Domain\Model\ReadOnlyPlayer;

final class SetupPlayer extends PlayerStatus
{
    public function startTurn(): PlayerStatus
    {
        return new PlayingPlayer();
    }

    public function startAction(GameContext $context): PlayerStatus
    {
        return $this;
    }

    public function performAction(ReadOnlyPlayer $player): PlayerStatus
    {
        return $this;
    }

    protected function toString(): string
    {
        return 'setup';
    }
}
