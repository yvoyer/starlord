<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Star\Component\State\InvalidStateTransitionException;
use StarLord\Domain\Model\Bonus\BlueCrystal;
use StarLord\Domain\Model\Bonus\GreenCrystal;
use StarLord\Domain\Model\Bonus\YellowCrystal;

final class TestPlayerTest extends TestCase
{
    /**
     * @var TestPlayer
     */
    private $player;

    /**
     * @var Card|MockObject
     */
    private $card;

    /**
     * @var UserAction|MockObject
     */
    private $action;

    public function setUp()
    {
        $this->card = $this->createMock(Card::class);
        $this->action = $this->createMock(UserAction::class);
        $this->player = TestPlayer::fromInt(99);
    }

    public function test_it_should_have_a_number_of_crystal_at_home_world()
    {
        $this->assertInstanceOf(Stash::class, $this->player->getHoard());
        $this->assertSame('[]', $this->player->getHoard()->toString());
    }

    public function test_it_should_have_a_credit_quantity()
    {
        $this->assertInstanceOf(Credit::class, $this->player->getCredit());
        $this->assertSame('0CRD', $this->player->getCredit()->toString());
    }

    public function test_it_should_have_a_deuterium_quantity()
    {
        $this->assertInstanceOf(Deuterium::class, $this->player->getDeuterium());
        $this->assertSame('0DEU', $this->player->getDeuterium()->toString());
    }

    public function test_it_should_have_a_colonies()
    {
        $this->assertInstanceOf(Population::class, $this->player->getPopulation());
        $this->assertSame('Population: 0', $this->player->getPopulation()->toString());
    }

    public function test_it_should_have_spaceships()
    {
        $this->assertInstanceOf(Armada::class, $this->player->getArmada());
        $this->assertSame('0 (Transport); 0 (Fighter); 0 (Cruiser)', $this->player->getArmada()->toString());
    }

    public function test_it_should_throw_exception_when_card_not_in_hand()
    {
        $this->assertFalse($this->player->hasCardInHand(34));

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Card with id "34" is not in hand of player "99".');
        $this->player->playCard(34);
    }

    public function test_it_should_throw_exception_when_card_already_in_hand()
    {
        $this->player->drawCard(34, $this->card);
        $this->assertTrue($this->player->hasCardInHand(34));

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Card with id "34" is already in hand of player "99".');
        $this->player->drawCard(34, $this->card);
    }

    public function test_it_should_add_transports()
    {
        $this->assertSame(0, $this->player->getArmada()->transports());
        $this->player->addTransports(1);
        $this->assertSame(1, $this->player->getArmada()->transports());
        $this->player->addTransports(3);
        $this->assertSame(4, $this->player->getArmada()->transports());
    }

    public function test_it_should_add_fighters()
    {
        $this->assertSame(0, $this->player->getArmada()->fighters());
        $this->player->addFighters(1);
        $this->assertSame(1, $this->player->getArmada()->fighters());
        $this->player->addFighters(3);
        $this->assertSame(4, $this->player->getArmada()->fighters());
    }

    public function test_it_should_add_cruisers()
    {
        $this->assertSame(0, $this->player->getArmada()->cruisers());
        $this->player->addCruisers(1);
        $this->assertSame(1, $this->player->getArmada()->cruisers());
        $this->player->addCruisers(3);
        $this->assertSame(4, $this->player->getArmada()->cruisers());
    }

    public function test_it_should_add_colons()
    {
        $this->assertSame(0, $this->player->getPopulation()->toInt());
        $this->player->addColons(1);
        $this->assertSame(1, $this->player->getPopulation()->toInt());
        $this->player->addColons(3);
        $this->assertSame(4, $this->player->getPopulation()->toInt());
    }

    public function test_playing_a_card_should_remove_it_from_the_hand()
    {
        $this->player->drawCard(1, $this->card);
        $this->assertFalse($this->player->hasCardInPlay($cardId = 1));
        $this->assertTrue($this->player->hasCardInHand($cardId));

        $this->player->playCard($cardId);

        $this->assertTrue($this->player->hasCardInPlay($cardId));
        $this->assertFalse($this->player->hasCardInHand($cardId));
    }

    public function test_it_should_collect_credit_from_small_yellow_crystals()
    {
        $this->assertSame(0, $this->player->getCredit()->toInt());

        $this->player->mineCrystal(YellowCrystal::withSize('small'));
        $this->player->collectResourcesFromCrystals();

        $this->assertSame(1, $this->player->getCredit()->toInt());
    }

    public function test_it_should_collect_credit_from_medium_yellow_crystals()
    {
        $this->assertSame(0, $this->player->getCredit()->toInt());

        $this->player->mineCrystal(YellowCrystal::withSize('medium'));
        $this->player->collectResourcesFromCrystals();

        $this->assertSame(2, $this->player->getCredit()->toInt());
    }

    public function test_it_should_collect_credit_from_large_yellow_crystals()
    {
        $this->assertSame(0, $this->player->getCredit()->toInt());

        $this->player->mineCrystal(YellowCrystal::withSize('large'));
        $this->player->collectResourcesFromCrystals();

        $this->assertSame(3, $this->player->getCredit()->toInt());
    }

    public function test_it_should_collect_colons_from_small_green_crystals()
    {
        $this->assertSame(0, $this->player->getPopulation()->toInt());

        $this->player->mineCrystal(GreenCrystal::withSize('small'));
        $this->player->collectResourcesFromCrystals();

        $this->assertSame(1, $this->player->getPopulation()->toInt());
    }

    public function test_it_should_collect_colons_from_medium_green_crystals()
    {
        $this->assertSame(0, $this->player->getPopulation()->toInt());

        $this->player->mineCrystal(GreenCrystal::withSize('medium'));
        $this->player->collectResourcesFromCrystals();

        $this->assertSame(2, $this->player->getPopulation()->toInt());
    }

    public function test_it_should_collect_colons_from_large_green_crystals()
    {
        $this->assertSame(0, $this->player->getPopulation()->toInt());

        $this->player->mineCrystal(GreenCrystal::withSize('large'));
        $this->player->collectResourcesFromCrystals();

        $this->assertSame(3, $this->player->getPopulation()->toInt());
    }

    public function test_it_should_collect_deuterium_from_small_blue_crystals()
    {
        $this->assertSame(0, $this->player->getDeuterium()->toInt());

        $this->player->mineCrystal(BlueCrystal::withSize('small'));
        $this->player->collectResourcesFromCrystals();

        $this->assertSame(1, $this->player->getDeuterium()->toInt());
    }

    public function test_it_should_collect_deuterium_from_medium_blue_crystals()
    {
        $this->assertSame(0, $this->player->getDeuterium()->toInt());

        $this->player->mineCrystal(BlueCrystal::withSize('medium'));
        $this->player->collectResourcesFromCrystals();

        $this->assertSame(2, $this->player->getDeuterium()->toInt());
    }

    public function test_it_should_collect_deuterium_from_large_blue_crystals()
    {
        $this->assertSame(0, $this->player->getDeuterium()->toInt());

        $this->player->mineCrystal(BlueCrystal::withSize('large'));
        $this->player->collectResourcesFromCrystals();

        $this->assertSame(3, $this->player->getDeuterium()->toInt());
    }

    public function test_it_should_put_the_player_in_stale_mode_on_colonize()
    {
        $this->assertFalse($this->player->isPlaying());

        $this->player->startAction();

        $this->assertTrue($this->player->isPlaying());
    }
}
