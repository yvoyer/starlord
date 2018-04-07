<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use StarLord\Domain\Model\Bonus\BlueCrystal;
use StarLord\Domain\Model\Bonus\Crystal;
use StarLord\Domain\Model\Bonus\GreenCrystal;
use StarLord\Domain\Model\Bonus\PurpleCrystal;
use StarLord\Domain\Model\Bonus\RedCrystal;
use StarLord\Domain\Model\Bonus\YellowCrystal;

final class ColoredPlanet implements Planet
{
    /**
     * @var WriteOnlyPlayer
     */
    private $owner;

    /**
     * @var Crystal
     */
    private $crystal;

    private function __construct(Crystal $generator)
    {
        $this->crystal = $generator;
    }

    /**
     * @param int $playerId
     * @param WriteOnlyPlayer $player
     */
    public function play(int $playerId, WriteOnlyPlayer $player)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param WriteOnlyPlayer $player
     */
    public function draw(WriteOnlyPlayer $player)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return bool
     */
    public function isColonized(): bool
    {
        return $this->owner instanceof WriteOnlyPlayer;
    }

    /**
     * @param WriteOnlyPlayer $owner
     */
    public function colonize(WriteOnlyPlayer $owner)
    {
//todo        $owner->colonize($this);
        $this->owner = $owner;
    }

    /**
     * @param WriteOnlyPlayer $player
     */
    public function collectResources(WriteOnlyPlayer $player)
    {
//        // todo planets should not be played, but colonized, resources shoul dbe alocated on resource phase
//        // todo use $player->isOwner($planetId) instead ??
        if (! $this->isColonized()) {
            throw new \LogicException("Cannot collect resources when planet is not colonized.");
        }

        $this->crystal->allocateResourceTo($player);
    }

    /**
     * @return Planet
     */
    public static function red(): Planet
    {
        return new self(RedCrystal::withSize('small'));
    }

    /**
     * @return Planet
     */
    public static function yellow(): Planet
    {
        return new self(YellowCrystal::withSize('small'));
    }

    /**
     * @return Planet
     */
    public static function blue(): Planet
    {
        return new self(BlueCrystal::withSize('small'));
    }

    /**
     * @return Planet
     */
    public static function purple(): Planet
    {
        return new self(PurpleCrystal::withSize('small'));
    }

    /**
     * @return Planet
     */
    public static function green(): Planet
    {
        return new self(GreenCrystal::withSize('small'));
    }
}
