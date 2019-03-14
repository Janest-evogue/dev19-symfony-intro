<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrmController
 * @package App\Controller
 *
 * @Route("/orm")
 */
class OrmController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('orm/index.html.twig', [
            'controller_name' => 'OrmController',
        ]);
    }

    /**
     * @param int $id
     * @Route("/user/{id}", requirements={"id": "\d+"})
     */
    public function getOneUser($id)
    {
        // gestionnaire d'entités de Doctrine
        $em = $this->getDoctrine()->getManager();
        /*
         * User::class = 'App\Entity\User'
         * Retourne un objet User dont les attributs sont settés
         * à partir de la bdd ou NULL si l'id n'est pas dans la table
         */
        $user = $em->find(User::class, $id);

        /* en version longue :
        $repository = $em->getRepository(User::class);
        $user = $repository->find($id);
        */

        dump($user);

        if (is_null($user)) {
            // renvoie une 404
            throw new NotFoundHttpException();
        }

        return $this->render(
            'orm/get_one_user.html.twig',
            [
                'user' => $user
            ]
        );
    }

    /**
     * @Route("/list-users")
     *
     * Pour accéder au gestionnaire d'entités par le conteneur de services,
     * on type le paramètre avec EntityManagerInterface
     */
    public function listUsers(EntityManagerInterface $em)
    {
        /** @var UserRepository $repository */
        $repository = $em->getRepository(User::class);

        // retourne tous les users de la bdd sous la forme
        // d'un tableau d'objets User ; un tableau vide si la table est vide
        $users = $repository->findAll();

        dump($users);

        return $this->render(
            'orm/list_users.html.twig',
            [
                'users' => $users
            ]
        );
    }

    /**
     * @Route("/search-email/{email}")
     */
    public function searchEmail(EntityManagerInterface $em, $email)
    {
        $repository = $em->getRepository(User::class);

        /*
         * findOneBy() quand on est sûr qu'il n'y aura pas plus d'un résultat
         * Retourne un objet User ou null
         */
        $user = $repository->findOneBy([
            'email' => $email
        ]);

        if (is_null($user)) {
            // renvoie une 404
            throw new NotFoundHttpException();
        }

        return $this->render(
            'orm/search_email.html.twig',
            [
                'user' => $user
            ]
        );
    }

    /**
     * @Route("/search-firstname/{firstname}")
     *
     * Passage du UserRepository par le conteneur de service
     */
    public function searchFirstname(UserRepository $repository, $firstname)
    {
        // retourne un tableau d'objets User filtrés
        // sur le prénom ; un tableau vide si pas de résultats
        $users = $repository->findBy([
            'firstname' => $firstname
        ]);

        return $this->render(
            'orm/list_users.html.twig',
            [
                'users' => $users
            ]
        );
    }

    /**
     * @Route("/another-user/{id_user}")
     * ParamConverter :
     * le paramètre dans l'url s'appelle id comme la clé primaire de User
     * En typant User le paramètre passé à la méthode, on récupère dans $user
     * un objet User défini à partir d'un SELECT sur la table user sur cet id
     *
     */
    public function getAnotherUser(User $user)
    {
        return $this->render(
            'orm/get_one_user.html.twig',
            [
                'user' => $user
            ]
        );
    }
}
