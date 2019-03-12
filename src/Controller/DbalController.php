<?php

namespace App\Controller;

use App\Model\Abonne;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DbalController
 * @package App\Controller
 *
 * @Route("/dbal")
 */
class DbalController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        // contient le dbal de Doctrine = surcouche à PDO
        /** @var Connection $cnx */
        $cnx = $this->getDoctrine()->getConnection();

        // utilisable comme PDO:
        $stmt = $cnx->query('SELECT * FROM abonne WHERE id = 1');
        dump($stmt->fetch());

        $abonne1 = $cnx->fetchAssoc(
            'SELECT * FROM abonne WHERE id = :id',
            [
                ':id' => 1
            ]
        );

        dump($abonne1);

        $abonnes = $cnx->fetchAll('SELECT * FROM abonne');

        dump($abonnes);

        $nb = $cnx->fetchColumn('SELECT count(*) FROM abonne');

        dump($nb);

//        $cnx->insert('abonne', ['prenom' => 'Liam']);
//
//        $cnx->update('abonne', ['prenom' => 'Gui'], ['id' => 1]);
//
//        $cnx->delete('abonne', ['id' => 6]);

        $abonne = new Abonne($cnx);
        dump($abonne->test());

        return $this->render(
            'dbal/index.html.twig',
            [

            ]
        );
    }
}
