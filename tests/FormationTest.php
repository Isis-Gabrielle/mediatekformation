<?php

namespace App\tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase{
    public function testGetPublishedAtString(){
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime('2025-12-18'));
        $this->assertEquals('18/12/2025', $formation->getPublishedAtString());
    }
}
