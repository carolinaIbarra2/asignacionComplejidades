<?php

namespace App\Repository;

use App\Entity\Soporte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repositorio para la entidad soporte
 * @extends ServiceEntityRepository<Soporte>
 *
 * @method Soporte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Soporte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Soporte[]    findAll()
 * @method Soporte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoporteRepository extends ServiceEntityRepository
{
    /**
     * Constructor del repositorio.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Soporte::class);
    }


    /**
     * Agrega un nuevo Soporte al repositorio.
     *
     * @param Soporte $entity
     * @param bool $flush
     */
    public function add(Soporte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * Elimina un Soporte del repositorio.
     *
     * @param Soporte $entity
     * @param bool $flush
     */
    public function remove(Soporte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Verifica si un soporte existe según su id.
     * @param int id id a verificar.
     * @return Soporte|null El soporte si existe, de lo contrario, null.
     */
    public function soporteExiste($id): ?Soporte
        {
            return $this->createQueryBuilder('c')
                ->andWhere('c.id = :id')
                ->setParameter('id', $id)
                ->getQuery()               
                ->getOneOrNullResult()
            ;
        }

//    /**
//     * @return Soporte[] Returns an array of Soporte objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Soporte
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
