<?php

namespace App\Service;
use App\Entity\Soporte;
use App\Repository\ClienteRepository;
use App\Repository\SoporteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Servicio para gestionar operaciones relacionadas con soportes.
 */
class SoporteService{

    private $soporteRepository;
    private $clienteRepository;
    private $entityManager;
    private $validator;


    /**
     * Constructor del servicio.
     *
     * @param SoporteRepository     $soporteRepository Repositorio de soportes.
     * @param ClienteRepository     $clienteRepository Repositorio de clientes.
     * @param EntityManagerInterface $entityManager    Manejador de entidades de Doctrine.
     * @param ValidatorInterface     $validator         Validador Symfony para validación de entidades.
     */
    public function __construct(SoporteRepository $soporteRepository,
    ClienteRepository $clienteRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator) {
        $this->soporteRepository = $soporteRepository;
        $this->clienteRepository = $clienteRepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        
    }

    /**
     * Crea un nuevo soporte a partir de los datos proporcionados.     
     * @param array $data Datos del soporte: clientes_id, descripcion, complejidad, fecha.     
     * @throws \InvalidArgumentException Si el formato de fecha es inválido
     */
    public function crearSoporte(array $data): void
    {

        $clientes = $data["cliente_id"];
        $descripcion = $data["descripcion"];
        $complejidad = $data["complejidad"];
        $fecha  = $data["fecha"];
        $fecha = \DateTime::createFromFormat('d-m-Y', $data['fecha']);

        if (!$fecha || $fecha->format('d-m-Y') !== $data['fecha'] ) {
            throw new \InvalidArgumentException('Formato de fecha inválido. Se esperaba el formato d-m-Y.');
        }  
        //verifico si el cliente existe
        $clienteExistente = $this->clienteRepository->findOneBy(['id' => $clientes]);

        if(!$clienteExistente){
            throw new \InvalidArgumentException('El cliente no existe.');
        }

        $soporte = new Soporte();

        $soporte->setDescripcion($descripcion);
        $soporte->setComplejidad($complejidad);
        $soporte->setFecha($fecha);

        $errors = $this->validator->validate($soporte);

        if(count($errors)>0){
            $errorMessages = [];
            foreach($errors as $error){
                $errorMessages[] = $error->getMessage();

            }
            throw new \InvalidArgumentException(implode(', ', $errorMessages));
        
        }

        //Agrego el soporte al cliente Existente
        $clienteExistente->addSoporte($soporte);

        //persistir los cambios en la BD
        $this->entityManager->persist($soporte);
        $this->entityManager->flush();  

    }

    
    /**
     * Obtiene información de los soportes junto con los detalles del cliente.
     * @return array Devuelve un array con los datos de los soportes.
     */    
    public function obtenerSoportes(): array
        {
            // Lógica para obtener los soportes
            $soportes = $this->soporteRepository->findAll(); 

            $soportesData = [];

            foreach ($soportes as $soporte) {

                $cliente = $soporte->getClientes();
               
                $soportesData[] = [
                    'id' => $soporte->getId(),
                    'descripcion' => $soporte->getDescripcion(),
                    'complejidad' => $soporte->getComplejidad(),
                    'fecha' => $soporte->getFechaFormateada(),                    
                    'cliente' =>$cliente->getNombre()." ".$cliente->getApellido()                       
                ];
            }
            return $soportesData;
    }
}
