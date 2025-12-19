<?php

namespace App\tests;

use App\Entity\Formation;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormationValidationTest extends KernelTestCase{
    
        public function getFormation(): Formation {
        $formation = new Formation();
        return $formation;
    }

    private function assertErrors(Formation $formation, int $nbErreursAttendues, string $message = "")
    {
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $errors = $validator->validate($formation);

        $this->assertCount($nbErreursAttendues, $errors, $message);
    }

    
    public function testValidPublishedAt()
    {
        $now = new DateTime();

        $this->assertErrors(
            $this->getFormation()->setPublishedAt($now),
            0,
            "La date du jour doit être valide"
        );

        $earlier = (new DateTime())->sub(new DateInterval("P5D"));

        $this->assertErrors(
            $this->getFormation()->setPublishedAt($earlier),
            0,
            "Une date passée doit être valide"
        );
    }
    
    public function testNonValidPublishedAt(){ 
        $later = (new DateTime())->add(new DateInterval("P1D"));
        $this->assertErrors($this->getFormation()->setPublishedAt($later), 1, "Une date ultérieure doit être invalide");
    }  
}