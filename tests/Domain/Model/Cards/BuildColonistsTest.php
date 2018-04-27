<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Cards;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Model\Credit;
use StarLord\Domain\Model\TestPlayer;

final class BuildColonistsTest extends TestCase
{
    public function test_it_should_cost_credit_to_play()
    {
        $player = TestPlayer::playingPlayer(1);
        $player->addCredit(new Credit(10));
        $player->drawCard(1, $card = new BuildColonists(1));

        $this->assertSame(10, $player->getCredit()->toInt());

        $card->whenPlayedBy($player);

        $this->assertSame(8, $player->getCredit()->toInt());
    }

    public function test_it_should_increase_population_of_player()
    {
        $player = TestPlayer::playingPlayer(1);
        $player->addCredit(new Credit(10));
        $player->drawCard(1, $card = new BuildColonists(3));

        $this->assertSame(0, $player->getPopulation()->toInt());

        $card->whenPlayedBy($player);

        $this->assertSame(3, $player->getPopulation()->toInt());
    }
}
