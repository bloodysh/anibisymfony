<?php

namespace App\Controller;

use App\Entity\Visite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Exposition;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Polyfill\Intl\Icu\DateFormat\DayOfWeekTransformer;


class CreateVisiteController extends AbstractController
{
    #[Route('/', name: 'app_create_visite')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $jauge=5;
        $lesExpos = $doctrine -> getRepository(Exposition::class)-> findAll();

        $visite = new Visite();
        $visite->setNbVisiteurAdulte(0);
        $visite->setNbVisiteurEnfant(0);
        $visite->setDateHeureArrivee(new \DateTime("now"));
        $visite->setDateHeureDepart(null);
        $nbAdultes=$request->get('nbAdultes');
        $nbEnfants=$request->get ('nbEnfants');
        $nbVisiteursEnCours=0;

        if (isset($nbAdultes, $nbEnfants)) {
            $visite->setNbVisiteurEnfant($request->get('nbEnfants'));
            $visite->setNbVisiteurAdulte($request->get('nbAdultes'));
            $nbVisiteursEnCours=$nbAdultes+ $nbEnfants;
        }

        foreach ($lesExpos as $uneExpo) {
            if ($request->get($uneExpo->getId())) {
                $visite->addExposition($uneExpo);
            }

        }

        $leTarif2=$request->get('2');
        $leTarif1=$request->get('1');
        //var_dump($leTarif2);
        //var_dump($visite->calculerTarif());

        return $this->render('create_visite/index.html.twig', [
            'controller_name' => 'CreateVisiteController',
            'lesExpos' => $lesExpos,
            'visite'=> $visite,
        ]);
    }
}
