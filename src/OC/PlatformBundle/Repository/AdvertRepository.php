<?php

namespace OC\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends \Doctrine\ORM\EntityRepository

{
    public function getAdverts($page, $nbPerPage)

    {
        $query = $this->createQueryBuilder('a')

            // Jointure sur l'attribut image
            ->leftJoin('a.image', 'i')
            ->addSelect('i')

            // Jointure sur l'attribut categories
            ->leftJoin('a.categories', 'c')
            ->addSelect('c')
            ->orderBy('a.date', 'DESC')

            // Jointure sur l'attribut advertskills
            ->leftJoin('a.advertskills', 's')
            ->addSelect('s')

            ->getQuery()
        ;
        $query

            // On définit l'annonce à partir de laquelle commencer la liste
            ->setFirstResult(($page-1) * $nbPerPage)

            // Ainsi que le nombre d'annonce à afficher sur une page
            ->setMaxResults($nbPerPage)
        ;

        // Enfin, on retourne l'objet Paginator correspondant à la requête construite
        // (n'oubliez pas le use correspondant en début de fichier)

        return new Paginator($query, true);
    }
  }
  

