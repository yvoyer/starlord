<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\TestPlayer;

final class BuildFighterTest extends TestCase
{
    /**
     * @var BuildFighter
     */
    private $card;

    public function setUp()
    {
        $this->card = BuildFighter::fromInt(1);
    }

    public function test_it_should_cost_credit()
    {
        $cardId = 12;
        $player = TestPlayer::fromInt(1);
        $player->addCredit(new Credit(10));
        $player->drawCard($cardId, $this->card);

        $this->assertSame(10, $player->getCredit()->toInt());

        $this->card->whenPlayedBy($player);

        $this->assertSame(7, $player->getCredit()->toInt());
    }

    public function test_it_should_add_ships_to_player()
    {
        $cardId = 12;
        $player = TestPlayer::fromInt(1);
        $player->addCredit(new Credit(10));
        $player->drawCard($cardId, $this->card);

        $this->assertSame(0, $player->getArmada()->fighters());

        $this->card->whenPlayedBy($player);

        $this->assertSame(1, $player->getArmada()->fighters());
    }
}
