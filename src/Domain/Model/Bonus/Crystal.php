<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Bonus;

use StarLord\Domain\Model\WriteOnlyPlayer;

abstract class Crystal
{
    /**
     * @var string
     */
    private $size;

    /**
     * @param string $size
     */
    protected function __construct(string $size)
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function size(): string
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function color(): string
    {
        $class = str_replace(__NAMESPACE__ . '\\', '', static::class);

        return strtolower(str_replace('Crystal', '', $class));
    }

    /**
     * @param Crystal $crystal
     *
     * @return bool
     */
    public function equalsTo(Crystal $crystal): bool
    {
        return $this->color() === $crystal->color() && $this->size() === $crystal->size();
    }

    /**
     * @param WriteOnlyPlayer $owner
     */
    abstract public function allocateResourceTo(WriteOnlyPlayer $owner);

    /**
     * @param string $size
     *
     * @return Crystal
     */
    public static function withSize(string $size): Crystal
    {
        switch ($size) {
            case 'small':
                break;

            case 'medium':
                break;

            case 'large':
                break;

            default:
                throw new \InvalidArgumentException(
                    sprintf('Size "%s" for crystal is not supported.', $size)
                );
        }

        return new static($size);
    }

    /**
     * @return Crystal
     */
    public static function randomSize(): Crystal
    {
        $sizes = ['small', 'medium', 'large'];

        return static::withSize($sizes[array_rand($sizes)]);
    }

    /**
     * @param string $color
     * @param string $size
     *
     * @return Crystal
     */
    public static function fromString(string $color, string $size): Crystal
    {
        $class = str_replace('Crystal', ucfirst($color) . 'Crystal', self::class);

        return call_user_func([$class, 'withSize'], $size);//$class::withSize($size);
    }

    /**
     * @return int
     */
    protected function getBonusBySize(): int
    {
        switch ($this->size()) {
            case 'small':
                return 1;

            case 'medium':
                return 2;

            case 'large':
                return 3;
        }

        throw new \InvalidArgumentException(
            sprintf('Size "%s" for crystal is not supported.', $this->size())
        );
    }
}
