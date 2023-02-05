<?php
declare(strict_types=1);

/** @noinspection PhpRedundantOptionalArgumentInspection */

namespace Tests;

use Exception;
use OutOfRangeException;
use PHPUnit\Framework\TestCase;
use Scientist\Chances\FractionalChance;
use TypeError;

/**
 * @see \Scientist\Chances\StandardChanceTest;
 */
class FractionalChanceTest extends TestCase
{
    /**
     * @var FractionalChance
     */
    private FractionalChance $chance;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->chance = new FractionalChance();
        parent::setUp();
    }


    /**
     * Test that chance will not take values that are too large
     */
    public function testChanceGreaterThanOneHundredPercent(): void
    {
        $this->expectException(OutOfRangeException::class);

        // 2/1 = 200% chance
        $numerator = 2;
        $denominator = 1;
        $this->chance->setProbability($denominator, $numerator);
    }


    /**
     * @return void
     */
    public function testDenominatorLessThanZero(): void
    {
        $this->expectException(OutOfRangeException::class);
        $this->chance->setProbability(-1);
    }


    /**
     * @throws Exception
     */
    public function testDenominatorOneShouldRun(): void
    {
        static::assertTrue($this->chance->setProbability(1)->shouldRun());
    }


    /**
     * @throws Exception
     */
    public function testDenominatorOrNumeratorZeroShouldNotRun(): void
    {
        static::assertFalse($this->chance->setProbability(0)->shouldRun());
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        static::assertFalse($this->chance->setProbability(0, 1)->shouldRun());
        static::assertFalse($this->chance->setProbability(1, 0)->shouldRun());
        static::assertFalse($this->chance->setProbability(0, 0)->shouldRun());
    }


    /**
     * @throws Exception
     */
    public function testEqualDenominatorNumeratorShouldRun(): void
    {
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        static::assertTrue($this->chance->setProbability(1, 1)->shouldRun());
        static::assertTrue($this->chance->setProbability(PHP_INT_MAX, PHP_INT_MAX)->shouldRun());
    }


    /**
     * @throws Exception
     */
    public function testNumeratorLessThanZero(): void
    {
        $this->expectException(exception: OutOfRangeException::class);
        $this->chance->setProbability(1, -1);
    }


    /**
     * @param int|mixed $denominator
     * @param int|mixed $numerator
     * @param mixed $expectException
     *
     * @throws Exception
     * @dataProvider testSetProbabilityProvider
     */
    public function testSetProbability(mixed $denominator, mixed $numerator, mixed $expectException): void
    {
        if (in_array($expectException, [OutOfRangeException::class, TypeError::class], true) === true) {
            $this->expectException($expectException);

        }
        $this->chance->setProbability($denominator, $numerator);
        [$chanceDenominator, $chanceNumerator] = $this->chance->getProbability();
        static::assertSame($denominator, $chanceDenominator);
        static::assertSame($numerator, $chanceNumerator);
    }


    /**
     * @group provider
     */
    public function testSetProbabilityProvider(): array
    {
        $testArray = [];

        // Any fractional chance up to 1/PHP_INT_MAX is possible
        $testArray[] = [10000, 1, false];
        $testArray[] = [PHP_INT_MAX, 1, false];

        // Numbers bigger than PHP_INT_MAX are not possible, they become floats
        $testArray[] = [PHP_INT_MAX + 1, 1, 'TypeError'];

        // Numerator and denominator are equal
        $testArray[] = [1, 1, false];
        $testArray[] = [PHP_INT_MAX, PHP_INT_MAX, false];

        // Numerator greater than denominator
        $testArray[] = [1, PHP_INT_MAX, OutOfRangeException::class];
        $testArray[] = [1, PHP_INT_MAX + 1, TypeError::class];

        return $testArray;
    }
}
