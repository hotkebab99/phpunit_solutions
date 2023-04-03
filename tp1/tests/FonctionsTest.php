<?php

use \PHPUnit\Framework\TestCase;

require('fonctions.php');

class FonctionsTest extends TestCase
{
    public function testAdd3And6Equals9() {
        $this->assertEquals(9, add(3, 6));
    }
}