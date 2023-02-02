[![Scientist](scientist.png)](https://github.com/daylerees/scientist)

# Scientist Fractional Chance

[![Packagist Version](https://img.shields.io/packagist/v/treetime-ca/scientist-chances-fractional.svg)](https://packagist.org/packages/treetime-ca/scientist-chances-fractional)
[![Packagist](https://img.shields.io/packagist/dt/treetime-ca/scientist-chances-fractional.svg)](https://packagist.org/packages/treetime-ca/scientist-chances-fractional)

Fractional chance class for use with the [Scientist Library](http://github.com/daylerees/scientist)

## 1. Installation

Require the latest version of Scientist Chances Fractional using [Composer](https://getcomposer.org/).

    composer require arcticlinux/scientist-chances-fractional

## 2. Usage

Using FractionalChance for experiment to run only 1 out of 100 times
```php
$chance = new FractionalChance();
// Setting experiment to run 1%, 1/100 times, default 1 out of value sent to setProbability
$chance->setProbability(100);
// Explicitly setting probability to 1% or 1/100 chance
$chance->setProbability(100, 1);
$experiment = (new Scientist\Laboratory)
  ->experiment('experiment title')
  ->control($controlCallback)
  ->chance($chance)
  ->trial('trial name', $trialCallback)
  ->matcher($matcher);
```

Using FractionalChance for experiment to run 33.3%, 1/3 of the time
```php
$chance = new FractionalChance();
$chance->setProbability(3,1);
$experiment = (new Scientist\Laboratory)
  ->experiment('experiment title')
  ->control($controlCallback)
  ->chance($chance)
  ->trial('trial name', $trialCallback)
  ->matcher($matcher);
```
