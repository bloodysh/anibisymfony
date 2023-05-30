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

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\KeyProtectedByPassword;


class CreateVisiteController extends AbstractController

{
    #[Route('/', name: 'app_create_visite')]



    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $writer = new PngWriter();



        $jauge=5;
        $lesExpos = $doctrine -> getRepository(Exposition::class)-> findAll();
        $lesVisites= $doctrine->getRepository(Visite::class)->findAll();

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



        if ($request->request->has('valider')) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($visite);
            $entityManager->flush();
            $encryptionPassword = 'your_encryption_password';
            $encryptedData = Crypto::encryptWithPassword($visite->getId().'; Enfants: '.$visite->getNbVisiteurEnfant().';Adultes: '.$visite->getNbVisiteurAdulte().'; Heure: '.$visite->getDateHeureArrivee()->format('Y-m-d H:i:s').';', $encryptionPassword);


            $qrCode = QrCode::create($encryptedData)
                ->setEncoding(new Encoding('UTF-8'))
                ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                ->setSize(300)
                ->setMargin(10)
                ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                ->setForegroundColor(new Color(0, 0, 0))
                ->setBackgroundColor(new Color(255, 255, 255));


            $result = $writer->write($qrCode);
            $dataUri = $result->getDataUri();
            $decryptedData = Crypto::decrypt($encryptedData, $encryptionPassword);

            return $this->render('create_visite/validation.html.twig',[
                'visite'=> $visite,
                'dataUri'=> $dataUri,
                'decryptedData' => $decryptedData,

        ]);
        }

        if ($request->request->has('Retour')){
            return $this->render('create_visite/index.html.twig', [
                'controller_name' => 'CreateVisiteController',
                'lesExpos' => $lesExpos,
                'visite'=> $visite,
            ]);
        }



        return $this->render('create_visite/index.html.twig', [
            'controller_name' => 'CreateVisiteController',
            'lesExpos' => $lesExpos,
            'visite'=> $visite,
        ]);
    }
}
