<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Assert\Assertion;
use Star\Component\Identity\Exception\EntityNotFoundException;
use StarLord\Domain\Model\Bonus\Crystal;
use StarLord\Domain\Model\Exception\NotCompletedActionException;
use StarLord\Domain\Model\Exception\PlayerActionException;
use StarLord\Domain\Model\State\PlayerStatus;
use StarLord\Domain\Model\State\SetupPlayer;

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

    /**
     * @var Colons
     */
    private $colons;

    /**
     * @var PlayerStatus
     */
    private $state;

    /**
     * @var string[]
     */
    private $remainingActions = [];

    /**
     * @param PlayerId $playerId
     */
    private function __construct(PlayerId $playerId)
    {
        $this->id = $playerId;
        $this->population = new Population();
        $this->credit = new Credit();
        $this->deuterium = new Deuterium();
        $this->hoard = Stash::emptyStash();
        $this->armada = new Armada();
        $this->level = new MiningLevel();
        $this->baseAttack = new BaseAttack();
        $this->state = new SetupPlayer();
        $this->colons = new Colons(0);
    }

    /**
     * @return PlayerId
     */
    public function getIdentity(): PlayerId
    {
        return $this->id;
    }

    /**
     * @param WriteOnlyPlanet $planet
     */
    public function collectResourcesFromPlanet(WriteOnlyPlanet $planet)
    {
        $planet->collectResources($this);
    }

    public function collectResourcesFromCrystals()
    {
        // todo put in another model
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
                    $this->id->toString()
                )
            );
        }
        $card->whenDraw($this);
        $this->hand[$cardId] = $card;
    }

    /**
     * @param int $cardId
     *
     * @return Card
     */
    public function playCard(int $cardId): Card
    {
        $card = $this->getCardFromHand($cardId);
//        $card->play($this->id, $this);

        $this->battlefield[$cardId] = $card;
        unset($this->hand[$cardId]);

        return $card;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->state->isActive();
    }

    public function turnIsDone(): bool
    {
        return $this->state->turnIsDone();
    }

    /**
     * @param GameContext $context
     * @param string[] $requiredActions
     */
    public function startAction(GameContext $context, array $requiredActions = [])
    {
        Assertion::allString($requiredActions);
        $this->state = $this->state->startAction($context);

        $this->remainingActions = $requiredActions;
    }

    /**
     * @param UserAction $action
     */
    public function performAction(UserAction $action)
    {
        $key = array_search($action->actionName(), $this->remainingActions);
        if (false === $key && $action->requiresPerform()) {
            throw new PlayerActionException(
                sprintf(
                    'Cannot perform the action "%s" when it is not required.',
                    $action->actionName()
                )
            );
        }

        unset($this->remainingActions[$key]);

//        if ($this->actionsAreCompleted()) {
            $this->state = $this->state->performAction($this);
  //      } else {
    //        $this->state = $this->state->continueAction();
      //  }
    }
//
//    public function startGame()
//    {
//        if (! $this->actionsAreCompleted()) {
//            throw new NotCompletedActionException(
//                sprintf(
//                    'Game cannot be started when player have some not completed actions "%s".',
//                    json_encode($this->remainingActions)
//                )
//            );
//        }
//
//        $this->state = $this->state->startGame();
//    }

    public function startTurn()
    {
        $this->state = $this->state->startTurn();
    }

    public function endTurn()
    {
        if (! $this->actionsAreCompleted()) {
            throw new NotCompletedActionException(
                sprintf(
                    'Cannot end turn when remaining actions are required %s.',
                    json_encode($this->remainingActions)
                )
            );
        }
        $this->state = $this->state->endTurn();
    }

    /**
     * @return bool
     */
    public function actionsAreCompleted(): bool
    {
        return empty($this->remainingActions);
    }

    /**
     * @param Colons $colons
     */
    public function loadColons(Colons $colons)
    {
        $this->colons = $this->colons->removeColons($colons->toInt());
    }

    /**
     * @return string[]
     */
    public function actionsToPerform(): array
    {
        return $this->remainingActions;
    }

    /**
     * Return the cards in hand
     *
     * @return int[] Card ids
     */
    public function cards(): array
    {
        return array_keys($this->hand);
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
        $this->colons = $this->colons->addColons($bonus);
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
     * @return Colons
     */
    public function availableColons(): Colons
    {
        return $this->colons;
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
     * @return Card
     * @throws EntityNotFoundException
     */
    private function getCardFromHand(int $cardId): Card
    {
        if (! $this->hasCardInHand($cardId)) {
            throw EntityNotFoundException::objectWithAttribute(Card::class, 'id', $cardId);
        }

        return $this->hand[$cardId];
    }

    /**
     * @param int $playerId
     *
     * @return TestPlayer
     */
    public static function fromInt(int $playerId): self
    {
        return new self(new PlayerId($playerId));
    }

    /**
     * @param int $playerId
     *
     * @return TestPlayer
     */
    public static function playingPlayer(int $playerId): self
    {
        $player = self::fromInt($playerId);
        $player->startTurn();

        return $player;
    }
}
