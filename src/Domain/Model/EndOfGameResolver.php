<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface EndOfGameResolver
{
    /**
     * @return bool
     */
    public function gameIsEnded(): bool;
}
