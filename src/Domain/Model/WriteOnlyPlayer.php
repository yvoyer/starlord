<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use StarLord\Domain\Model\Bonus\Crystal;

interface WriteOnlyPlayer extends ReadOnlyPlayer
{
    /**
     * @return PlayerId
     */
    public function getIdentity(): PlayerId;

    /**
     * @param WriteOnlyPlanet $planet
     * @deprecated todo remove
     */
    public function collectResourcesFromPlanet(WriteOnlyPlanet $planet);

    /**
     * Collect resources from owned crystals
     * @deprecated todo remove
     */
    public function collectResourcesFromCrystals();

    /**
     * @param int $cardId
     * @param Card $card
     */
    public function drawCard(int $cardId, Card $card);

    /**
     * @param int $cardId
     *
     * @return Card
     */
    public function playCard(int $cardId): Card;

    /**
     * @param int $cardId
     *
     * @return bool
     */
    public function hasCardInHand(int $cardId): bool;

    /**
     * @param string[] $requiredActions The required actions needed to finish the action.
     */
    public function startAction(array $requiredActions = []);

    /**
     * @param UserAction $action
     */
    public function performAction(UserAction $action);

    /**
     * @param Colons $colons
     */
    public function loadColons(Colons $colons);

    public function endTurn();

    /**
     * @param int $number
     */
    public function addTransports(int $number);

    /**
     * @param int $number
     */
    public function addFighters(int $number);

    /**
     * @param int $number
     */
    public function addCruisers(int $number);

    /**
     * @param Credit $credit
     */
    public function addCredit(Credit $credit);

    /**
     * @param Cost $cost
     */
    public function pay(Cost $cost);

    /**
     * @param Deuterium $deuterium
     */
    public function addDeuterium(Deuterium $deuterium);

    /**
     * @param int $bonus
     */
    public function addColons(int $bonus);

    /**
     * @param int $bonus
     */
    public function increaseBaseAttack(int $bonus);

    /**
     * @param Crystal $crystal
     */
    public function mineCrystal(Crystal $crystal);

    /**
     * @param int $bonus
     */
    public function increaseMiningLevel(int $bonus);
}
