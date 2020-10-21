<?php

class Trajet
{
    public $etapes;

    public function __construct() {
        $this->etapes = [];
    }

    public function pushEtape($etape) {
        $this->etapes[] = $etape;
    }

    public function popEtape() {
        return array_pop($this->etapes);
    }

    public function getDistanceTotale() {
        $distance_totale = 0;

        foreach ($this->etapes as $etape) {
            $distance_totale += $etape->kilometres;
        }

        return $distance_totale;
    }
}