<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

/**
 * User action that uses Command pattern
 */
interface UserAction
{
    /**
     * @return ActionName
     */
    public function name(): ActionName;

    /**
     * @param WriteOnlyPlayer $player todo could be an ActionContext instead
     * todo rename to execute()
     */
    public function perform(WriteOnlyPlayer $player);
}
