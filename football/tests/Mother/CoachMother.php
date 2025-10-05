<?php

namespace App\Tests\Mother;

use App\Entity\Club;
use App\Entity\Coach;
use Faker\Factory;

class CoachMother
{
    static function create(
        ?string  $name   = null,
        ?string  $surname = null,
        ?Club    $club   = null
    ): Coach
    {
        $faker = Factory::create();
        $name = $name ?? $faker->name();
        $surname = $surname ?? $faker->name();
        $mother = new Coach($name, $surname);
        if (!is_null($club)) {
            $mother->setClub($club);
        }
        return $mother;
    }
}