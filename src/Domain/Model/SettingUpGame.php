<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

final class SettingUpGame implements GameContext
{
    /**
     * @return bool
     */
    public function gameIsStarted(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function gameIsEnded(): bool
    {
        return false;
    }
}
