<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

/**
 * Store of required actions in order to finish a card action.
 */
final class UserActionStore
{
    private function __construct()
    {
        // constant store
    }

    /**
     * @return ActionName
     */
    public static function moveShip(): ActionName
    {
        return new ActionName('move-ship');
    }
}
