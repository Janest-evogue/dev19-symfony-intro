<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/")
     *
     */
    public function index()
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/hello")
     */
    public function hello()
    {
        return $this->render('index/hello.html.twig');
    }

    /**
     * Une route avec une partie variable (entre accolades)
     * le $qui en paramètre de la méthode contient la valeur
     * de cette partie variable
     *
     * @Route("/bonjour/{qui}")
     */
    public function bonjour($qui)
    {
        return $this->render(
            'index/bonjour.html.twig',
            // le tableau en 2e paramètre de la méthode render()
            // permet de passer des variables à la vue :
            // le nom de la variable est la clé dans le tableau
            [
                'quidam' => $qui
            ]
        );
    }

    /**
     * valeur par défaut pour la partie variable :
     * La route matche /salut/unNom : $qui vaut unNom
     * et matche aussi /salut ou /salut/ : $qui vaut 'le monde'
     *
     * @Route("/salut/{qui}", defaults={"qui": "le monde"})
     */
    public function salut($qui)
    {
        return $this->render(
            'index/salut.html.twig',
            [
                'qui' => $qui
            ]
        );
    }

    /**
     * La route matche /coucou/Julien et /coucou/Julien-Anest
     *
     * @Route("/coucou/{prenom}-{nom}", defaults={"nom": ""})
     */
    public function coucou($prenom, $nom)
    {
        $qui = rtrim($prenom . ' ' . $nom);

        return $this->render(
            'index/salut.html.twig',
            [
                'qui' => $qui
            ]
        );
    }

    /**
     * heure doit forcément être un nombre (\d+ en expression régulière)
     *
     * @Route("/bonsoir/{heure}",
     *     defaults={"heure": null},
     *     requirements={"heure": "\d+"})
     */
    public function bonsoir($heure)
    {
        if (is_null($heure)) {
            $heure = date('h');
        }

        return $this->render(
            'index/bonsoir.html.twig',
            [
                'heure' => $heure
            ]
        );
    }
}
