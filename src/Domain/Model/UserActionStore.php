<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

/**
 * Store of required actions in order to finish a card action.
 */
final class UserActionStore
{
    const MOVE_SHIP = 'move-ship';
    const MINE_PLANET = 'mine-planet';
    const SELECT_HOME_WORLD = 'select-home-world';

    private function __construct()
    {
        // constant store
    }
}
