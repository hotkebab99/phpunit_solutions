<?php

use \PHPUnit\Framework\TestCase;

class VoitureTest extends TestCase
{
    public $voiture;

    protected function setUp(): void {
        $this->voiture = new Voiture('Volkswagen', 'Golf', 24000, DIESEL);
    }

    public function testRetourDetailsEstCorrect() {
        $this->assertEquals("Volkswagen Golf - 24000 km - 20000 km", $this->voiture->getDetails());
    }

    /**
     * @dataProvider etapeProvider
     */
    public function testEtapeAugmenteKilometrageEtDiminueKilometrageAvantRevision($kilometrage, $kilometrage_total, $kilometrage_avant_revision) {
        $this->voiture->ajouterEtape($kilometrage);

        $this->assertEquals($kilometrage_total, $this->voiture->kilometrage);
        $this->assertEquals($kilometrage_avant_revision, $this->voiture->kilometrageAvantRevision);
    }

    public function testEtapeRetourneTrueSiRevision() {
        $this->assertTrue($this->voiture->ajouterEtape(30000));
    }

    public function testEtapeRetourneFalseSiPasRevision() {
        $this->assertFalse($this->voiture->ajouterEtape(2000));
    }

    public function testEtapeNegativeRetourneException() {
        $this->expectException(EtapeNonValideException::class);
        $this->voiture->ajouterEtape(-250);
    }

    public function testEtapeTropGrandeRetourneException() {
        $this->expectException(EtapeNonValideException::class);
        $this->voiture->ajouterEtape(31000);
    }

    public function testEntretenirReinitialiseKmAvantRevision() {
        $this->voiture->ajouterEtape(825);

        $this->voiture->entretenir();

        $this->assertEquals(20000, $this->voiture->kilometrageAvantRevision);
    }

    public function etapeProvider() {
        return [
            [100, 24100, 19900],
            [0, 24000, 20000],
            [1000, 25000, 19000]
        ];
    }

}
