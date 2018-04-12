<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface Planet extends Card
{
    /**
     * @param WriteOnlyPlayer $owner
     */
    public function colonize(WriteOnlyPlayer $owner);

    /**
     * @return bool
     */
    public function isColonized(): bool;

    /**
     * @param WriteOnlyPlayer $player
     */
    public function collectResources(WriteOnlyPlayer $player);
}
