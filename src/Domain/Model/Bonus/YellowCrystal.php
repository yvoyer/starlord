<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Bonus;

use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\WriteOnlyPlayer;

final class YellowCrystal extends Crystal
{
    /**
     * @param WriteOnlyPlayer $owner
     */
    public function allocateResourceTo(WriteOnlyPlayer $owner)
    {
        $owner->addCredit(new Credit($this->getBonusBySize()));
    }
}
