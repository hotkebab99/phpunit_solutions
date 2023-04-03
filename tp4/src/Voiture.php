<?php

class Voiture
{
    public $marque;
    public $modele;
    public $kilometrage;
    public $energie;
    public $kilometrageAvantRevision;

    protected $planificateur;

    public function setPlanificateur($planificateur) {
        $this->planificateur = $planificateur;
    }

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

    public function ajouterEtape(Etape $etape) {
        return $this->ajouterKilometres($etape->kilometres);
    }

    public function ajouterTrajet($ville_depart, $ville_arrivee) {
        $trajet = $this->planificateur->getTrajet($ville_depart, $ville_arrivee);

        $kilometrage = $trajet->getDistanceTotale($trajet);

        return $this->ajouterKilometres($kilometrage);
    }

    private function ajouterKilometres($kilometrage) {
        if($kilometrage < 0 || $kilometrage > 1.5 * $this->kilometrageAvantRevision)
            throw new EtapeNonValideException();

        $this->kilometrage += $kilometrage;
        $this->kilometrageAvantRevision -= $kilometrage;

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