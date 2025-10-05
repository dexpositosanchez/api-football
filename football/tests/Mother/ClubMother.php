<?php

namespace App\Tests\Mother;

use App\Entity\Club;
use Faker\Factory;

class ClubMother
{
    static function create(
        ?string $name   = null,
        ?float  $budget = null
    ): Club
    {
        $faker = Factory::create();
        $name = $name ?? $faker->name();
        $budget = $budget ?? $faker->randomFloat(2);
        $mother = new Club($name, $budget);

        return $mother;
    }
}