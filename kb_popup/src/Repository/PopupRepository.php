<?php

namespace PrestaShop\Module\Kb_Popup\Repository;

use PrestaShop\Module\Kb_Popup\Entity\Popup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Popup|null find($id, $lockMode = null, $lockVersion = null)
 * @method Popup|null findOneBy(array $criteria, array $orderBy = null)
 * @method Popup[]    findAll()
 * @method Popup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PopupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Popup::class);
    }

    /**
     * @return Popup[] Returns an array of Popup objects
     */
    public function findByProduct($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.idProduct = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Popup
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
