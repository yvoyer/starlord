<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Bonus;

use StarLord\Domain\Model\WriteOnlyPlayer;

final class GreenCrystal extends Crystal
{
    /**
     * @param WriteOnlyPlayer $owner
     */
    public function allocateResourceTo(WriteOnlyPlayer $owner)
    {
        $owner->addColons($this->getBonusBySize());
    }
}
