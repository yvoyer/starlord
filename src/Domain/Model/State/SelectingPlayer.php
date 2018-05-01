<?php declare(strict_types=1);

namespace StarLord\Domain\Model\State;

use StarLord\Domain\Model\GameContext;

final class SelectingPlayer extends PlayerStatus
{
    public function isActive(): bool
    {
        return true;
    }

    public function startAction(GameContext $context): PlayerStatus
    {
        if () {

        }
        return new parent::startAction($context);
    }

    protected function toString(): string
    {
        return 'selecting';
    }
}
