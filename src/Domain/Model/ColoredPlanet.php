<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use StarLord\Domain\Model\Bonus\BlueCrystal;
use StarLord\Domain\Model\Bonus\Crystal;
use StarLord\Domain\Model\Bonus\GreenCrystal;
use StarLord\Domain\Model\Bonus\PurpleCrystal;
use StarLord\Domain\Model\Bonus\RedCrystal;
use StarLord\Domain\Model\Bonus\YellowCrystal;
use StarLord\Domain\Model\Exception\InvalidPlanetOwnerException;
use StarLord\Domain\Model\Exception\InvalidUsageException;

final class ColoredPlanet implements WriteOnlyPlanet
{
    /**
     * @var PlayerId|null
     */
    private $owner;

    /**
     * @var Crystal todo change to color
     */
    private $crystal;

    /**
     * @var Colons
     */
    private $population;

    /**
     * @var Stash
     */
    private $stash;

    private function __construct(Crystal $generator)
    {
        $this->crystal = $generator;
        $this->population = new Colons(0);
        $this->stash = Stash::emptyStash();
    }

    /**
     * @return Stash
     */
    public function stash(): Stash
    {
        return $this->stash;
    }

    /**
     * @param PlayerId $playerId
     */
    public function mine(PlayerId $playerId)
    {
        if (! $this->isColonized()) {
            throw new InvalidPlanetOwnerException('Cannot mine a planet that was never colonized.');
        }

        if (! $playerId->match($this->owner)) {
            throw new InvalidPlanetOwnerException('Cannot mine a planet that you do not own.');
        }

        $this->stash = $this->stash->addCrystal(
            Crystal::fromString(
                $this->crystal->color(), 'small'
            )
        );
    }

    /**
     * @param WriteOnlyPlayer $player
     */
    public function whenPlayedBy(WriteOnlyPlayer $player)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param WriteOnlyPlayer $player
     */
    public function whenDraw(WriteOnlyPlayer $player)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @return bool
     */
    public function isColonized(): bool
    {
        return (bool) $this->owner;
    }

    /**
     * @param PlayerId $owner
     * @param Colons $colons
     */
    public function colonize(PlayerId $owner, Colons $colons)
    {
        $this->owner = $owner;
        $this->population = $this->population->addColons($colons->toInt());
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
     * @return Colons
     */
    public function population(): Colons
    {
        return $this->population;
    }

    /**
     * @return PlayerId
     */
    public function ownerId(): PlayerId
    {
        if (! $this->isColonized()) {
            throw new InvalidUsageException('Planet is not colonized yet, cannot return a valid owner.');
        }

        return $this->owner;
    }

    /**
     * @return WriteOnlyPlanet
     */
    public static function red(): WriteOnlyPlanet
    {
        return new self(RedCrystal::withSize('small'));
    }

    /**
     * @return WriteOnlyPlanet
     */
    public static function yellow(): WriteOnlyPlanet
    {
        return new self(YellowCrystal::withSize('small'));
    }

    /**
     * @return WriteOnlyPlanet
     */
    public static function blue(): WriteOnlyPlanet
    {
        return new self(BlueCrystal::withSize('small'));
    }

    /**
     * @return WriteOnlyPlanet
     */
    public static function purple(): WriteOnlyPlanet
    {
        return new self(PurpleCrystal::withSize('small'));
    }

    /**
     * @return WriteOnlyPlanet
     */
    public static function green(): WriteOnlyPlanet
    {
        return new self(GreenCrystal::withSize('small'));
    }
}
