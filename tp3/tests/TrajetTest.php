<?php

use PHPUnit\Framework\TestCase;

class TrajetTest extends TestCase
{
    public $trajet;

    protected function setUp(): void {
        $this->trajet = new Trajet;
    }

    public function testTrajetTableauAZeroEtapeParDefaut()
    {
        $this->assertIsArray($this->trajet->etapes);
        $this->assertEmpty($this->trajet->etapes);
    }

    public function testAjouterEtapeTrajetAugmenteTailleDeUn()
    {
        $this->trajet->pushEtape(new Etape(125));

        $this->assertEquals(1, count($this->trajet->etapes));
    }

    public function testPopEtapeRetourneDerniereValeurTableauEtapesEtDimiueTailleDeUn()
    {
        $etape1 = new Etape(125);
        $etape2 = new Etape(423);

        $this->trajet->pushEtape($etape1);
        $this->trajet->pushEtape($etape2);


        $this->assertEquals($etape2, $this->trajet->popEtape());
        $this->assertEquals(1, count($this->trajet->etapes));

    }

    public function testPopTableauEtapeVideRetourneNull()
    {
        $this->assertNull($this->trajet->popEtape());
    }

    public function testKilometrageTrajetVideAZero()
    {
        $this->assertEquals(0, $this->trajet->getDistanceTotale());
    }

    public function testKilometragePlusieursEtapesTotalCorrect()
    {
        $etape1 = new Etape(125);
        $etape2 = new Etape(423);
        $etape3 = new Etape(225);

        $this->trajet->pushEtape($etape1);
        $this->trajet->pushEtape($etape2);
        $this->trajet->pushEtape($etape3);

        $this->assertEquals(773, $this->trajet->getDistanceTotale());
    }
}
