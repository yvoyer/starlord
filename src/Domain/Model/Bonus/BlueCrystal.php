<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Bonus;

use StarLord\Domain\Model\Deuterium;
use StarLord\Domain\Model\WriteOnlyPlayer;

final class BlueCrystal extends Crystal
{
    /**
     * @param WriteOnlyPlayer $owner
     */
    public function allocateResourceTo(WriteOnlyPlayer $owner)
    {
        $owner->addDeuterium(new Deuterium($this->getBonusBySize()));
    }
}
