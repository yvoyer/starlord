<?php declare(strict_types=1);

namespace StarLord\Domain\Model;

use Webmozart\Assert\Assert;

final class Galaxy implements World
{
    /**
     * @var Planet[]
     */
    private $planets;

    private function __construct(array $planets)
    {
        Assert::allIsInstanceOf($planets, Planet::class);
        $this->planets = $planets;
    }

    /**
     * @return Planet[]
     */
    public function allPlanets(): array
    {
        return $this->planets;
    }

    /**
     * @param int $playerId
     *
     * @return Planet[]
     */
    public function allColonizedPlanetsOfPlayer(int $playerId): array
    {
        return array_filter($this->planets, function (Planet $planet) {
            return $planet->isColonized();
        });
    }

    /**
     * @param int $quantity
     *
     * @return World
     */
    public static function withPlanetCount(int $quantity): World
    {
        $planets = [];
        for ($i = 0; $i < $quantity; $i ++) {
            $planets[] = ColoredPlanet::blue();
        }

        return new self($planets);
    }
}
