<?php

use \PHPUnit\Framework\TestCase;
use \PHPUnit\Framework\Attributes\DataProvider;

class VoitureTest extends TestCase
{
    public $voiture;

    protected function setUp(): void {
        $this->voiture = new Voiture('Volkswagen', 'Golf', 24000, Energie::DIESEL);
    }

    public function testRetourDetailsEstCorrect() {
        $this->assertEquals("Volkswagen Golf - 24000 km - 20000 km", $this->voiture->getDetails());
    }

    #[DataProvider('etapeProvider')]
    public function testEtapeAugmenteKilometrageEtDiminueKilometrageAvantRevision($etape, $kilometrage_total, $kilometrage_avant_revision) {
        $this->voiture->ajouterEtape($etape);

        $this->assertEquals($kilometrage_total, $this->voiture->kilometrage);
        $this->assertEquals($kilometrage_avant_revision, $this->voiture->kilometrageAvantRevision);
    }

    public function testEtapeRetourneTrueSiRevision() {
        $this->assertTrue($this->voiture->ajouterEtape(new Etape(30000)));
    }

    public function testEtapeRetourneFalseSiPasRevision() {
        $this->assertFalse($this->voiture->ajouterEtape(new Etape(2000)));
    }

    public function testEtapeNegativeRetourneException() {
        $this->expectException(EtapeNonValideException::class);
        $this->voiture->ajouterEtape(new Etape(-250));
    }

    public function testEtapeTropGrandeRetourneException() {
        $this->expectException(EtapeNonValideException::class);
        $this->voiture->ajouterEtape(new Etape(31000));
    }

    public function testTrajetVideRetourneFalseEtNeChangeRien() {
        $planificateur = $this->createMock(Planificateur::class);

        $planificateur->method('getTrajet')
            ->with("Paris", "Paris")
            ->willReturn(new Trajet);

        $this->voiture->setPlanificateur($planificateur);
        
        $this->assertFalse($this->voiture->ajouterTrajet("Paris", "Paris"));
        $this->assertEquals(24000, $this->voiture->kilometrage);
        $this->assertEquals(20000, $this->voiture->kilometrageAvantRevision);
    }

    public function testCourtTrajetRetourneFalseEtChangeValeurs() {
        $planificateur = $this->createMock(Planificateur::class);

        $trajet = new Trajet();
        $trajet->pushEtape(new Etape(50));
        $trajet->pushEtape(new Etape(100));

        $planificateur->method('getTrajet')
            ->with("Paris", "Rouen")
            ->willReturn($trajet);

        $this->voiture->setPlanificateur($planificateur);
        
        $this->assertFalse($this->voiture->ajouterTrajet("Paris", "Rouen"));
        $this->assertEquals(24150, $this->voiture->kilometrage);
        $this->assertEquals(19850, $this->voiture->kilometrageAvantRevision);
    }

    public function testLongTrajetRetourneTrueEtChangeValeurs() {
        $planificateur = $this->createMock(Planificateur::class);

        $trajet = new Trajet();
        $trajet->pushEtape(new Etape(10000));
        $trajet->pushEtape(new Etape(15000));


        $planificateur->method('getTrajet')
            ->with("Rome", "Lille")
            ->willReturn($trajet);

        $this->voiture->setPlanificateur($planificateur);
        
        $this->assertTrue($this->voiture->ajouterTrajet("Rome", "Lille"));
        $this->assertEquals(49000, $this->voiture->kilometrage);
        $this->assertEquals(-5000, $this->voiture->kilometrageAvantRevision);
    }
   
    public function testEntretenirReinitialiseKmAvantRevision() {
        $this->voiture->ajouterEtape(new Etape(825));

        $this->voiture->entretenir();

        $this->assertEquals(20000, $this->voiture->kilometrageAvantRevision);
    }

    public static function etapeProvider() {
        return [
            [new Etape(100), 24100, 19900],
            [new Etape(0), 24000, 20000],
            [new Etape(1000), 25000, 19000]
        ];
    }

}
