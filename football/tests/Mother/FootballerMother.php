<?php

namespace App\Tests\Mother;

use App\Entity\Club;
use App\Entity\Footballer;
use Faker\Factory;

class FootballerMother
{
    static function create(
        ?string  $name   = null,
        ?string  $surname = null,
        ?Club    $club   = null
    ): Footballer
    {
        $faker = Factory::create();
        $name = $name ?? $faker->name();
        $surname = $surname ?? $faker->name();
        $mother = new Footballer($name, $surname);
        if (!is_null($club)) {
            $mother->setClub($club);
        }
        return $mother;
    }
}