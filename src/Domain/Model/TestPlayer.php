<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use StarLord\Domain\Model\Bonus\Crystal;
use StarLord\Domain\Model\Cards\NotFoundCard;

class TestPlayer implements ReadOnlyPlayer, WriteOnlyPlayer
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Population
     */
    private $population;

    /**
     * @var Credit
     */
    private $credit;

    /**
     * @var Deuterium
     */
    private $deuterium;

    /**
     * @var BaseAttack
     */
    private $baseAttack;

    /**
     * @var MiningLevel
     */
    private $level;

    /**
     * @var Stash
     */
    private $hoard;

    /**
     * @var Armada
     */
    private $armada;

    /**
     * @var Card[]
     */
    private $hand = [];

    /**
     * @var Card[]
     */
    private $battlefield = [];

    public function __construct(int $playerId)
    {
        $this->id = $playerId;
        $this->population = new Population();
        $this->credit = new Credit();
        $this->deuterium = new Deuterium();
        $this->hoard = Stash::emptyStash();
        $this->armada = new Armada();
        $this->level = new MiningLevel();
        $this->baseAttack = new BaseAttack();
    }

    /**
     * @param Planet $planet
     */
    public function collectResourcesFromPlanet(Planet $planet)
    {
        $planet->collectResources($this);
    }

    public function collectResourcesFromCrystals()
    {
        foreach ($this->hoard->crystals() as $crystal) {
            $crystal->allocateResourceTo($this);
        }
    }

    /**
     * @param int $cardId
     * @param Card $card
     */
    public function drawCard(int $cardId, Card $card)
    {
        if ($this->hasCardInHand($cardId)) {
            throw new \LogicException(
                sprintf(
                    'Card with id "%s" is already in hand of player "%s".',
                    $cardId,
                    $this->id
                )
            );
        }
        $card->draw($this);
        $this->hand[$cardId] = $card;
    }

    /**
     * @param int $cardId
     */
    public function playCard(int $cardId)
    {
        $card = $this->getCardFromHand($cardId);
        $card->play($this->id, $this);

        $this->battlefield[$cardId] = $card;
        unset($this->hand[$cardId]);
    }

    /**
     * @param int $cardId
     *
     * @return bool
     */
    public function hasCardInHand(int $cardId): bool
    {
        return array_key_exists($cardId, $this->hand);
    }

    /**
     * @param int $cardId
     *
     * @return bool
     */
    public function hasCardInPlay(int $cardId): bool
    {
        return array_key_exists($cardId, $this->battlefield);
    }

    /**
     * @param int $bonus
     */
    public function addColons(int $bonus)
    {
        $this->population = $this->population->addColons($bonus);
    }

    /**
     * @return Population
     */
    public function getPopulation(): Population
    {
        return $this->population;
    }

    /**
     * @param int $quantity
     */
    public function increaseMiningLevel(int $quantity)
    {
        $this->level = $this->level->increase($quantity);
    }

    /**
     * @return MiningLevel
     */
    public function getMiningLevel(): MiningLevel
    {
        return $this->level;
    }

    /**
     * @param Crystal $crystal
     */
    public function mineCrystal(Crystal $crystal)
    {
        $this->hoard = $this->hoard->addCrystal($crystal);
    }

    /**
     * @return Stash
     */
    public function getHoard(): Stash
    {
        return $this->hoard;
    }

    /**
     * @param int $number
     */
    public function addTransports(int $number)
    {
        $this->armada = $this->armada->addTransports($number);
    }

    /**
     * @param int $number
     */
    public function addFighters(int $number)
    {
        $this->armada = $this->armada->addFighters($number);
    }

    /**
     * @param int $number
     */
    public function addCruisers(int $number)
    {
        $this->armada = $this->armada->addCruisers($number);
    }

    /**
     * @return Armada
     */
    public function getArmada(): Armada
    {
        return $this->armada;
    }

    /**
     * @param int $bonus
     */
    public function increaseBaseAttack(int $bonus)
    {
        $this->baseAttack = $this->baseAttack->addBonus($bonus);
    }

    /**
     * @return BaseAttack
     */
    public function getBaseAttack(): BaseAttack
    {
        return $this->baseAttack;
    }

    /**
     * @param Credit $bonus
     */
    public function addCredit(Credit $bonus)
    {
        $this->credit = $this->credit->add($bonus);
    }

    /**
     * @return Credit
     */
    public function getCredit(): Credit
    {
        return $this->credit;
    }

    /**
     * @param Credit $credit
     */
    private function removeCredit(Credit $credit)
    {
        $this->credit = $this->credit->subtract($credit);
    }

    /**
     * @param Cost $cost
     */
    public function pay(Cost $cost)
    {
        $this->removeCredit($cost->credit());
        $this->removeDeuterium($cost->deuterium());
    }

    /**
     * @param Deuterium $deuterium
     */
    public function addDeuterium(Deuterium $deuterium)
    {
        $this->deuterium = $this->deuterium->add($deuterium);
    }

    /**
     * @return Deuterium
     */
    public function getDeuterium(): Deuterium
    {
        return $this->deuterium;
    }

    /**
     * @param Deuterium $deuterium
     */
    private function removeDeuterium(Deuterium $deuterium)
    {
        $this->deuterium = $this->deuterium->remove($deuterium);
    }

    /**
     * @param int $cardId
     *
     * @return Card
     * @throws \LogicException
     */
    private function getCardFromHand(int $cardId): Card
    {
        $card = new NotFoundCard($cardId);
        if ($this->hasCardInHand($cardId)) {
            $card =$this->hand[$cardId];
        }

        return $card;
    }
}
