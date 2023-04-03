<?php

class Voiture
{
    public $marque;
    public $modele;
    public $kilometrage;
    public $energie;
    public $kilometrageAvantRevision;


    public function __construct($marque, $modele, $kilometrage, $energie) {
        $this->marque = $marque;
        $this->modele = $modele;
        $this->kilometrage = $kilometrage;
        $this->energie = $energie;
        $this->initKmAvantRevision();
    }

    public function getDetails() {
        return "$this->marque $this->modele - $this->kilometrage km - $this->kilometrageAvantRevision km";
    }

    public function ajouterEtape(int $kilometres) {
        if($kilometres < 0 || $kilometres > 1.5 * $this->kilometrageAvantRevision)
            throw new EtapeNonValideException();

        $this->kilometrage += $kilometres;
        $this->kilometrageAvantRevision -= $kilometres;

        return $this->kilometrageAvantRevision < 0;
    }

    public function entretenir() {
        $this->initKmAvantRevision();
    }

    private function initKmAvantRevision() {
        switch($this->energie) {
            case Energie::DIESEL: $this->kilometrageAvantRevision = 20000; break;
            case Energie::ESSENCE: $this->kilometrageAvantRevision = 15000; break;
            case Energie::ELECTRIQUE: $this->kilometrageAvantRevision = 30000; break;
        }
    }
}