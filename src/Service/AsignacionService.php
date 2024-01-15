<?php

namespace App\Service;
use App\Entity\Asignacion;
use App\Entity\Trabajador;
use App\Repository\AsignacionRepository;
use App\Repository\SoporteRepository;
use App\Repository\TrabajadorRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Servicio para gestionar asignaciones y trabajadores.
 */
class AsignacionService{

    private $asignacionRepository;
    private $trabajadoresRepository;
    private $soportesRepository;
    private $entityManager;
    private $validator;


    /**
     * Constructor del servicio.
     *
     * @param AsignacionRepository      $asignacionRepository
     * @param TrabajadorRepository      $trabajadoresRepository
     * @param SoporteRepository         $soportesRepository
     * @param EntityManagerInterface    $entityManager
     * @param ValidatorInterface        $validator
     */
    public function __construct(AsignacionRepository $asignacionRepository,
    TrabajadorRepository $trabajadoresRepository, SoporteRepository $soportesRepository,
    EntityManagerInterface $entityManager, ValidatorInterface $validator) {
        $this->asignacionRepository = $asignacionRepository;
        $this->trabajadoresRepository = $trabajadoresRepository;
        $this->soportesRepository = $soportesRepository;
        $this->entityManager = $entityManager;   
        $this->validator = $validator;     
    }


    /**
     * Crea una nueva asignación.
     * @param array $data Datos de la asignación.
     * @throws \InvalidArgumentException
     * @return Trabajador|null
     */
    public function crearAsignacion(array $data): ?Trabajador
    {

        $id = $data['id_soporte'];
        $fecha = \DateTime::createFromFormat('d-m-Y', $data['fecha_asignacion']);
        $fechavalidacion = $fecha->format('Y-m-d');

        if($fechavalidacion<date('Y-m-d')){
            throw new \InvalidArgumentException('La fecha debe ser igual o mayor que la actual'); 
        }


        //verificar si el soporte existe
        $soporteExistente = $this->soportesRepository->soporteExiste($id);

        if(!$soporteExistente){
             //el soporte ya existe, envío excepcion
             throw new \InvalidArgumentException('no existe un soporte con este id');      
        }
        

        //verificar si el soporte esta asignado
        $soporteAsignado = $this->asignacionRepository->verificarSoporte($id);
        if($soporteAsignado){
            //el soporte ya esta asignado, envío excepcion
            throw new \InvalidArgumentException('El soporte ya esta asignado');
        }


        $asignacion = new Asignacion();

        $trabajador = $this->obtenerTrabajadorMenorCargaDia();
        $soporte = $this->soportesRepository->find($id);        

        

         //asignar los datos         
         $asignacion->setFechaAsignacion($fecha);
         $asignacion->setTrabajadores($trabajador);
         $asignacion->setSoportes($soporte);

         $errors = $this->validator->validate($soporte);

         if(count($errors)>0){
            $errorMessages = [];
            foreach($errors as $error){
                $errorMessages[] = $error->getMessage();

            }
            throw new \InvalidArgumentException(implode(', ', $errorMessages));
        
        }
                 
         //persistir los cambios
         $this->entityManager->persist($asignacion);
         $this->entityManager->flush();      
         
         return $trabajador;
    }

    

    
    /**
     * Lista los trabajadores con carga acumulada para una fecha específica.
     *
     * @param array $data Datos con la fecha.
     * @return array
     */
    public function listarTrabajadorConCargaAcumulada(array $data): array
    {
        $fechaString = $data['fecha'];
        $fechaDT = \DateTime::createFromFormat('d-m-Y', $fechaString);
        $fecha = $fechaDT->format('Y-m-d');    
       
        $listados = $this->obtenerCargaAcumuladaPorDia($fecha);

        $listadosData = [];

        foreach($listados as $listado){
            $listadosData[] = [
                'id' => $listado['id'],
                'nombre_trabajador' => $listado['nombre'],
                'complejidad_acumulada' => $listado['acumulado']
            ];
        }
        return $listadosData;

        }
  
    
    /**
     * Obtiene el trabajador con la menor carga laboral para el día actual.
     *
     * @return Trabajador|null
     */
    public function obtenerTrabajadorMenorCargaDia(): ?Trabajador
    {
        $fechaAsignacion = date('Y-m-d');
        
        $nombreTrabajador = $this->asignacionRepository->trabajadorConMenorCargaDia($fechaAsignacion);
        
        if($nombreTrabajador !== null){

            $trabajador = $this->trabajadoresRepository->find($nombreTrabajador["id"]);
            
            return $trabajador;
        }

        return null;
    }

    /**
     * Obtiene la carga acumulada de los trabajadores para una fecha específica.
     *
     * @param string $fecha Fecha en formato 'Y-m-d'.
     * @return array
     */
    public function obtenerCargaAcumuladaPorDia($fecha): array
    {      
        
        $complejidadTrabajadores = $this->asignacionRepository->cargaLaboralTrabajadores($fecha);

        return $complejidadTrabajadores;   
    }

}