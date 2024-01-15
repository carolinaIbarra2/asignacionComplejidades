<?php

namespace App\Repository;

use App\Entity\Asignacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Asignacion>
 *
 * @method Asignacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asignacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asignacion[]    findAll()
 * @method Asignacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AsignacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asignacion::class);
    }

    /**
     * Persiste la entidad Asignacion en la base de datos.
     * @param Asignacion $entity
     * @param bool       $flush
     */
    public function add(Asignacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Elimina la entidad Asignacion de la base de datos.
     * @param Asignacion $entity
     * @param bool       $flush
     */
    public function remove(Asignacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * Obtiene la información del trabajador con la menor carga laboral en un día específico.
     *
     * @param string $fecha La fecha para la cual se realiza la consulta.
     * @return array La información del trabajador con la menor carga laboral.
     */ 
    public function trabajadorConMenorCargaDia( $fecha): array
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT tr.id, tr.nombre, COALESCE(SUM(sop.complejidad), 0) as acumulado 
        FROM trabajador as tr 
        LEFT JOIN asignacion as asi on tr.id = asi.trabajadores_id
        LEFT JOIN soporte as sop on asi.soportes_id = sop.id  
        AND DATE(asi.fecha_asignacion)= :fecha
        GROUP BY tr.id 
        ORDER BY acumulado ASC
        LIMIT 1;";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['fecha' => $fecha]);
       
        
        return $resultSet->fetchAllAssociative()[0];
    }


     /**
     * Obtiene la carga laboral de todos los trabajadores en un día específico.
     *
     * @param string $fecha La fecha para la cual se realiza la consulta.
     * @return array Un array con la carga laboral de todos los trabajadores.
     */
    public function cargaLaboralTrabajadores($fecha): array
    {

        $conn = $this->getEntityManager()->getConnection();
       
        $sql = "
        SELECT tr.id, tr.nombre, COALESCE(SUM(sop.complejidad), 0) as acumulado 
        FROM trabajador as tr 
        LEFT JOIN asignacion as asi on tr.id = asi.trabajadores_id
        LEFT JOIN soporte as sop on asi.soportes_id = sop.id  
        AND DATE(asi.fecha_asignacion) = :fecha
        GROUP BY tr.id 
        ORDER BY acumulado ASC;";

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['fecha' => $fecha]);
       
        
        return $resultSet->fetchAllAssociative();
    }


    /**
     * Verifica si existe una asignación asociada a un soporte.
     *
     * @param int $id_soporte
     * @return Asignacion|null
     */
    public function verificarSoporte(int $id_soporte)  : ?Asignacion  
        {
            return $this->createQueryBuilder('c')
                ->andWhere('c.soportes = :id')
                ->setParameter('id', $id_soporte)
                ->getQuery()               
                ->getOneOrNullResult()
            ;
        }



//    /**
//     * @return Asignacion[] Returns an array of Asignacion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Asignacion
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
