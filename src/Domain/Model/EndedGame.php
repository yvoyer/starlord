<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

final class EndedGame implements GameContext
{
    /**
     * @return bool
     */
    public function gameIsStarted(): bool
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return bool
     */
    public function gameIsEnded(): bool
    {
        return true;
    }
}
