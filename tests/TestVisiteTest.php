<?php

namespace App\Tests;

use App\Entity\Exposition;
use App\Entity\Visite;
use PHPUnit\Framework\TestCase;

class TestVisiteTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testCalculerTarif(): void
    {
        $visite = new Visite();
        //cas visite avec nbAdultes et nbEnfants à null et 0 expo;
        $this->assertEquals(0, $visite->calculerTarif(), 'Erreur avec visiteurs à null, 0 expos');

        //cas visite avec nb adultes et enfants à null et une expo
        $expo1 = new Exposition();
        $expo1->setNomExpo('Test1');
        $expo1->setActive(true);
        $expo1->setTarifAdulte(10);
        $expo1->setTarifEnfant(5);
        $visite->addExposition($expo1);
        $this->assertEquals(0, $visite->calculerTarif(), 'Erreur avec visiteurs à null et 1 expo');

        //cas visite avec nb adultes et enfants à 0 et une expo
        $visite->setNbVisiteurEnfant(0);
        $visite->setNbVisiteurAdulte(0);
        $this->assertEquals(0, $visite->calculerTarif(), 'Erreur avec visiteurs à 0 et 1 expo');

        //cas visite avec nb adultes et enfants != 0 et une expo
        $visite->setNbVisiteurEnfant(2);
        $visite->setNbVisiteurAdulte(1);
        $this->assertEquals(20, $visite->calculerTarif(), 'Erreur avec des visiteurs et 1 expo');

        //cas visite avec nb adultes et enfants != 0 et plusiseurs (2) expos
        $expo2 = new Exposition();
        $expo2->setNomExpo('Test2');
        $expo2->setActive(true);
        $expo2->setTarifAdulte(20);
        $expo2->setTarifEnfant(10);
        $visite->addExposition($expo2);
        $this->assertEquals(60, $visite->calculerTarif(), 'Erreur avec des visiteurs et des expos');
    }

}
