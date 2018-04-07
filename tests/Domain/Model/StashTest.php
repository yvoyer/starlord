<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Model\Bonus\BlueCrystal;
use StarLord\Domain\Model\Bonus\GreenCrystal;
use StarLord\Domain\Model\Bonus\PurpleCrystal;
use StarLord\Domain\Model\Bonus\RedCrystal;
use StarLord\Domain\Model\Bonus\YellowCrystal;

final class StashTest extends TestCase
{
    public function test_it_should_add_crystal_by_color()
    {
        $original = Stash::emptyStash();
        $this->assertSame(0, $original->ofColor('blue'));
        $this->assertSame(0, $original->ofColor('red'));

        $new = $original->addCrystal(RedCrystal::randomSize());
        $this->assertSame(0, $original->ofColor('blue'));
        $this->assertSame(0, $original->ofColor('red'));
        $this->assertSame(0, $new->ofColor('blue'));
        $this->assertSame(1, $new->ofColor('red'));

        $other = $new->addCrystal(BlueCrystal::randomSize());
        $this->assertSame(1, $other->ofColor('blue'));
        $this->assertSame(1, $other->ofColor('red'));
    }

    public function test_it_should_add_crystal_by_size()
    {
        $original = Stash::emptyStash();
        $this->assertSame(0, $original->ofSize('small'));
        $this->assertSame(0, $original->ofSize('medium'));
        $this->assertSame(0, $original->ofSize('large'));

        $new = $original->addCrystal(YellowCrystal::withSize('small'));
        $this->assertSame(0, $original->ofSize('small'));
        $this->assertSame(0, $original->ofSize('medium'));
        $this->assertSame(0, $original->ofSize('large'));
        $this->assertSame(1, $new->ofSize('small'));
        $this->assertSame(0, $new->ofSize('medium'));
        $this->assertSame(0, $new->ofSize('large'));

        $other = $new->addCrystal(PurpleCrystal::withSize('medium'));
        $this->assertSame(1, $other->ofSize('small'));
        $this->assertSame(1, $other->ofSize('medium'));
        $this->assertSame(0, $other->ofSize('large'));

        $another = $other->addCrystal(GreenCrystal::withSize('large'));
        $this->assertSame(1, $another->ofSize('small'));
        $this->assertSame(1, $another->ofSize('medium'));
        $this->assertSame(1, $another->ofSize('large'));
    }

    public function test_it_should_return_the_crystals()
    {
        $original = Stash::emptyStash();
        $stash_one = $original->addCrystal($blue = BlueCrystal::randomSize());

        $this->assertCount(1, $crystals = $stash_one->crystals());
        $this->assertTrue($crystals[0]->equalsTo($blue));

        $stash_two = $stash_one->addCrystal($purple = PurpleCrystal::randomSize());

        $this->assertCount(2, $crystals = $stash_two->crystals());
        $this->assertTrue($crystals[0]->equalsTo($blue));
        $this->assertTrue($crystals[1]->equalsTo($purple));
    }

    public function test_it_should_return_the_number_of_crystal()
    {
        $s1 = Stash::emptyStash();
        $s2 = $s1->addCrystal(RedCrystal::withSize('small'));
        $this->assertSame(1, $s2->ofColor('red'));
        $s3 = $s2->addCrystal(RedCrystal::withSize('small'));
        $this->assertSame(2, $s3->ofColor('red'));
        $s4 = $s3->addCrystal(RedCrystal::withSize('small'));
        $this->assertSame(3, $s4->ofColor('red'));
        $s5 = $s4->addCrystal(RedCrystal::withSize('medium'));
        $this->assertSame(4, $s5->ofColor('red'));
    }
}
