<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

final class GameSettings
{
    /**
     * Card at start of game
     */
    const STARTING_CARDS = 5;

    /**
     * Transport at start of game
     */
    const STARTING_TRANSPORTS = 2;

    /**
     * Fighters at start of game
     */
    const STARTING_FIGHTERS = 1;

    /**
     * Cruisers at start of game
     */
    const STARTING_CRUISERS = 0;

    /**
     * Credit at start of game
     */
    const STARTING_CREDIT = 10;

    /**
     * Deuterium at start of game
     */
    const STARTING_DEUTERIUM = 5;

    /**
     * Colons at start of game
     */
    const STARTING_COLONS = 1;
}
