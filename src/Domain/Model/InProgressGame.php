<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

final class InProgressGame implements GameContext
{
    /**
     * @return bool
     */
    public function gameIsStarted(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function gameIsEnded(): bool
    {
        return false;
    }
}
