<?php


namespace App\Model;


use Doctrine\DBAL\Connection;

/**
 * Class Abonne
 * @package App\Model
 */
class Abonne
{
    private $cnx;

    public function __construct(Connection $cnx)
    {
        $this->cnx = $cnx;
    }

    public function test()
    {
        return $this->cnx->fetchAll('SELECT * FROM abonne');
    }
}
