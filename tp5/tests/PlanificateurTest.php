<?php

use PHPUnit\Framework\TestCase;

class PlanificateurTest extends TestCase
{
    public function testPlanificateurRetourneTrajetVide()
    {
        $planificateur = new Planificateur();
        
        $this->assertEmpty($planificateur->getTrajet("Paris", "Rouen")->etapes);
    }
}
