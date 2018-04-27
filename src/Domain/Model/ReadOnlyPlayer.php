<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

interface ReadOnlyPlayer
{
    /**
     * @return Population
     */
    public function getPopulation(): Population;

    /**
     * @return Credit
     */
    public function getCredit(): Credit;

    /**
     * @return Stash
     */
    public function getHoard(): Stash;

    /**
     * @return Deuterium
     */
    public function getDeuterium(): Deuterium;

    /**
     * @return Armada
     */
    public function getArmada(): Armada;

    /**
     * @return BaseAttack
     */
    public function getBaseAttack(): BaseAttack;

    /**
     * @return MiningLevel
     */
    public function getMiningLevel(): MiningLevel;

    /**
     * @return bool
     */
    public function isActive(): bool;

    /**
     * @return bool
     */
    public function turnIsDone(): bool;

    /**
     * @return Colons
     */
    public function availableColons(): Colons;
}
