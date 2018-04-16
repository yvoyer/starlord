<?php //declare(strict_types=1);
//
//namespace StarLord\Domain\Model\Actions;
//
//use StarLord\Domain\Model\ActionName;
//use StarLord\Domain\Model\PlanetId;
//use StarLord\Domain\Model\WriteOnlyShip;
//use StarLord\Domain\Model\UserAction;
//use StarLord\Domain\Model\WriteOnlyPlayer;
//
//final class MoveShipAction implements UserAction
//{
//    /**
//     * @var WriteOnlyShip
//     */
//    private $ship;
//
//    /**
//     * @var PlanetId
//     */
//    private $planet;
//
//    /**
//     * @param WriteOnlyShip $ship
//     * @param PlanetId $planet
//     */
//    public function __construct(WriteOnlyShip $ship, PlanetId $planet)
//    {
//        $this->ship = $ship;
//        $this->planet = $planet;
//    }
//
//    /**
//     * @return ActionName
//     */
//    public function name(): ActionName
//    {
//        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
//    }
//
//    /**
//     * @param WriteOnlyPlayer $player
//     */
//    public function perform(WriteOnlyPlayer $player)
//    {
//        // player may be RuleContext
//        // on start of context, a rule can be built
//        $this->ship->moveTo($this->planet);
//    }
//}
