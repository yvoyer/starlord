<?php //declare(strict_types=1);
//
//namespace StarLord\Domain\Model\Cards;
//
//use Assert\Assertion;
//use StarLord\Domain\Model\Card;
//use StarLord\Domain\Model\PlayerId;
//use StarLord\Domain\Model\WriteOnlyPlayer;
//
//final class PrototypeCard implements Card
//{
//    /**
//     * @var Card[]
//     */
//    private $abilities;
//
//    /**
//     * @param Card[] $abilities
//     */
//    public function __construct(array $abilities)
//    {
//        Assertion::allIsInstanceOf($abilities, Card::class);
//        $this->abilities = $abilities;
//    }
//
//    /**
//     * @param PlayerId $playerId
//     * @param WriteOnlyPlayer $player
//     */
//    public function whenPlayedBy(PlayerId $playerId, WriteOnlyPlayer $player)
//    {
//        foreach ($this->abilities as $ability) {
//            $ability->whenPlayedBy($playerId, $player);
//        }
//    }
//
//    /**
//     * @param WriteOnlyPlayer $player
//     */
//    public function whenDraw(WriteOnlyPlayer $player)
//    {
//        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
//    }
//}
