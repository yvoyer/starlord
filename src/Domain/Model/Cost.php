<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface Cost
{
    /**
     * @return Credit
     */
    public function credit(): Credit;

    /**
     * @return Deuterium
     */
    public function deuterium(): Deuterium;
}
