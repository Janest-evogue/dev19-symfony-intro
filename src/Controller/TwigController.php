<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TwigController
 * @package App\Controller
 *
 * Préfixe de route pour toutes les routes définies dans la classe
 * @Route("/twig")
 */
class TwigController extends AbstractController
{
    /**
     * Avec le préfixe de route de la classe,
     * l'url de cette page est /twig/ ou /twig
     * @Route("/")
     */
    public function index()
    {
        $str = 'une chaîne de caractère';

        // équivalent d'un var_dump qui s'affiche
        //dans la barre de debug (icône cible)
        dump($str);

        $auj = new \DateTime();

        return $this->render(
            'twig/index.html.twig',
            [
                'auj' => $auj
            ]
        );
    }
}
