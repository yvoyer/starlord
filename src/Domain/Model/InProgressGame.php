<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

final class InProgressGame implements EndOfGameResolver
{
    /**
     * @return bool
     */
    public function gameIsEnded(): bool
    {
        return false;
    }
}
