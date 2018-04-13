<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface UserAction
{
    /**
     * @return ActionName
     */
    public function name(): ActionName;

    /**
     * @param WriteOnlyPlayer $player
     */
    public function perform(WriteOnlyPlayer $player);
}
