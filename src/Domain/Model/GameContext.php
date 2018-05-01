<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface GameContext
{
    /**
     * @return bool
     */
    public function gameIsStarted(): bool;

    /**
     * @return bool
     */
    public function gameIsEnded(): bool;
}
