<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HttpController
 * @package App\Controller
 *
 * @Route("/http")
 */
class HttpController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('http/index.html.twig', [
            'controller_name' => 'HttpController',
        ]);
    }

    /**
     * @Route("/request")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function request(Request $request)
    {
        dump($_GET);

        /*
         * $request->query est l'attribut de l'objet Request qui
         * fait référence à $_GET, sa méthode all() retourne la totalité
         * de $_GET
         */
        dump($request->query->all());

        /*
         * $_GET['nom']
         * null si 'nom' n'est pas dans $_GET
         */
        dump($request->query->get('nom'));

        // 2e paramètre optionnel : valeur par défaut si 'nom' n'est pas dans $_GET
        dump($request->query->get('nom', 'anonyme'));

        // GET ou POST
        dump($request->getMethod());

        if ($request->isMethod('POST')) {

            // $request->request est l'attribut de l'objet Request qui
            //  fait référence à $_POST. Fonctionne comme $request->query
            dump($request->request->all());

            dump($request->request->get('nom'));
        }

        // raccourci (éq. $_REQUEST)
        dump($request->get('prenom'));
        dump($request->get('nom'));

        return $this->render('http/request.html.twig');
    }

    /**
     * @Route("/session")
     */
    public function session(Request $request)
    {
        // pour accéder à la session
        $session = $request->getSession();

        // pour ajouter des éléments à la session
        $session->set('nom', 'Anest');
        $session->set('prenom', 'Julien');

        // les éléments stockés par l'objet Session le sont
        // dans $_SESSION['_sf2_attributes']
        dump($_SESSION);

        // tous les éléments de la session
        dump($session->all());

        // accéder à un élément
        dump($session->get('nom'));

        // supprime un élement de la session
        $session->remove('prenom');

        dump($session->all());

        // vide la session
        $session->clear();

        dump($session->all());

        return $this->render('http/session.html.twig');
    }

    /**
     * @Route("/response")
     */
    public function response(Request $request)
    {
        // une réponse qui contient du texte brut
        $response = new Response('Ma réponse');

        // 127.0.0.1:8000/http/response?type=twig
        if ($request->query->get('type') == 'twig') {
            // $this->render() retourne un objet Response
            // dont le contenu est le HTML construit par le template
            $response = $this->render('http/response.html.twig');
        // 127.0.0.1:8000/http/response?type=json
        } elseif ($request->query->get('type') == 'json') {
            $exemple = [
                'nom' => 'Anest',
                'prenom' => 'Julien'
            ];

            // encode $exemple en json et retourne la réponse
            // avec le Content-Type application/json dans les entêtes
            $response = new JsonResponse($exemple);

            //$response = new Response(json_encode($exemple));

        // http://127.0.0.1:8000/http/response?found=no
        } elseif ($request->query->get('found') == 'no') {
            // on jette cette exception pour retourner une 404
            throw new NotFoundHttpException();
        // http://127.0.0.1:8000/http/response?redirect=index
        } elseif ($request->query->get('redirect') == 'index') {
            // redirige vers la route qui a pour nom app_index_index
            $response = $this->redirectToRoute('app_index_index');
        // http://127.0.0.1:8000/http/response?redirect=bonjour
        } elseif ($request->query->get('redirect') == 'bonjour') {
            // redirection vers une route avec une partie variable
            $response = $this->redirectToRoute(
                'app_index_bonjour',
                [
                    'qui' => 'le monde'
                ]
            );
        }

        // une action de contrôleur doit toujours renvoyer
        // un objet instance de Response
        return $response;
    }

    /**
     * @Route("/flash")
     */
    public function flash()
    {
        $this->addFlash('success', 'Message de confirmation');
        $this->addFlash('success', 'Autre message de confirmation');
        $this->addFlash('error', "Message d'erreur");

        return $this->redirectToRoute('app_http_flashed');
    }

    /**
     * @Route("/flashed")
     */
    public function flashed()
    {
        return $this->render('http/flashed.html.twig');
    }

    /*
     * Faire un formulaire en post avec :
     * - email (text)
     * - message (textarea)
     *
     * Si le formulaire est envoyé, vérifier que les 2 champs sont remplis
     * Si non, afficher un message d'erreur
     * Si oui, enregistrer les valeurs en session et rediriger vers une
     * nouvelle page qui les affiche (avec les sauts de ligne pour le message)
     * et vide la session
     * Dans cette page, si la session est vide, rediriger vers le formulaire
     */
}
