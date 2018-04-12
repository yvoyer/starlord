<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Model\Bonus\BlueCrystal;
use StarLord\Domain\Model\Bonus\RedCrystal;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\TestPlayer;

final class MineCrystalTest extends TestCase
{
    public function test_it_should_add_crystal_of_color_on_planet_of_player_choice()
    {
        $player = new TestPlayer(1);
        $player->addCredit(new Credit(10));
        $card = new MineCrystal(1, RedCrystal::withSize('small'));
        $player->drawCard(1, $card);

        $this->assertSame(0, $player->getHoard()->ofColor('red'));

        $player->playCard(1);

        $this->assertSame(1, $player->getHoard()->ofColor('red'));
    }

    public function test_it_should_cost_credit()
    {
        $player = new TestPlayer(1);
        $player->addCredit(new Credit(10));
        $card = new MineCrystal(1, BlueCrystal::withSize('medium'));
        $player->drawCard(1, $card);

        $this->assertSame(10, $player->getCredit()->toInt());

        $player->playCard(1);

        $this->assertSame(5, $player->getCredit()->toInt());
    }
}
