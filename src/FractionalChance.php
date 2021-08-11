<?php

namespace Scientist\Chances;

use Exception;

class FractionalChance implements Chance
{
    private $denominator;
    private $numerator;




    /**
     * @return array
     */
    public function getProbability(): array
    {
        return [$this->denominator, $this->numerator];
    }




    /**
     * The default probability is numerator 1 out of $denominator
     * e.g. 1/1   for 100% chance
     *      1/100 for 1% chance
     *
     * @param int      $denominator
     * @param int|null $numerator
     *
     * @return $this
     * @throws Exception
     */
    public function setProbability(int $denominator, ?int $numerator = 1): self
    {
        if ($denominator < 0 || $numerator < 0) {
            throw new Exception('numerator and denominator for probability must be greater than zero');
        }
        if ($denominator > 0 & $numerator > 0 && $numerator / $denominator > 1) {
            throw new Exception('experiment probability cannot be greater than 100 percent');
        }

        $this->denominator = $denominator;
        $this->numerator = $numerator;

        return $this;
    }




    /**
     * Determine if the experiment should run
     *
     * @throws Exception
     */
    public function shouldRun(): bool
    {
        // Do not run if the fractional chance has 0 chance of a fraction,
        // or the chance is a fraction out of a possible 0 chance
        if ($this->numerator === 0 || $this->denominator === 0) {
            return false;
        }
        if ($this->numerator === $this->denominator) {
            return true;
        }

        $random = random_int(1, $this->denominator);

        return $random <= $this->numerator;
    }
}
