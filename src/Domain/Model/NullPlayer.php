<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use StarLord\Domain\Model\Bonus\Crystal;

final class NullPlayer implements WriteOnlyPlayer
{
    /**
     * @param Planet $planet
     */
    public function collectResourcesFromPlanet(Planet $planet)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * Collect resources from owned crystals
     */
    public function collectResourcesFromCrystals()
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param int $cardId
     * @param Card $card
     */
    public function drawCard(int $cardId, Card $card)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param int $cardId
     */
    public function playCard(int $cardId)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param int $number
     */
    public function addTransports(int $number)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param int $number
     */
    public function addFighters(int $number)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param int $number
     */
    public function addCruisers(int $number)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param Credit $credit
     */
    public function addCredit(Credit $credit)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param Cost $cost
     */
    public function pay(Cost $cost)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param Deuterium $deuterium
     */
    public function addDeuterium(Deuterium $deuterium)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param int $bonus
     */
    public function addColons(int $bonus)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param int $bonus
     */
    public function increaseBaseAttack(int $bonus)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param Crystal $crystal
     */
    public function mineCrystal(Crystal $crystal)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param int $bonus
     */
    public function increaseMiningLevel(int $bonus)
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
