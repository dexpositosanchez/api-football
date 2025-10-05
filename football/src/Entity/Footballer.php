<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FootballerRepository::class)]
#[ORM\Table(name: "footballers")]
class Footballer extends Person {}