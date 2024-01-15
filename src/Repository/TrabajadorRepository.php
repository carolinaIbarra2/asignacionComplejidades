<?php

namespace App\Repository;

use App\Entity\Trabajador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repositorio para la entidad Trabajador
 * @extends ServiceEntityRepository<Trabajador>
 *
 * @method Trabajador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trabajador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trabajador[]    findAll()
 * @method Trabajador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrabajadorRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trabajador::class);
    }


    /**
     * Agrega un nuevo Trabajador al repositorio.
     *
     * @param Trabajador $entity
     * @param bool $flush
     */
    public function add(Trabajador $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * Elimina un Trabajador del repositorio.
     *
     * @param Trabajador $entity
     * @param bool $flush
     */
    public function remove(Trabajador $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Comprueba si un Trabajador con el nombre dado ya existe.
     *
     * @param string $nombre
     * @return Trabajador|null
     */
    public function trabajadorExiste($nombre): ?Trabajador
        {
            return $this->createQueryBuilder('c')
                ->andWhere('c.nombre = :nombre')
                ->setParameter('nombre', $nombre)
                ->getQuery()               
                ->getOneOrNullResult()
            ;
        }


//    /**
//     * @return Trabajador[] Returns an array of Trabajador objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trabajador
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
