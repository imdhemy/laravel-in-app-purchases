<?php

namespace Tests;

use Faker\Generator;

/**
 * Class Faker
 * This class is a wrapper for the Faker library.
 */
class Faker
{
    /**
     * @var Generator
     */
    private Generator $faker;

    /**
     * @param Generator $faker
     */
    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->faker->$name(...$arguments);
    }
}
