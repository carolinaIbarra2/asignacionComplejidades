<?php

namespace App\Service;
use App\Entity\Trabajador;
use App\Repository\TrabajadorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Servicio para gestionar operaciones relacionadas con trabajadores.
 */
class TrabajadorService{

    private $trabajadorRepository;
    private $validator;
   

    /**
     * Constructor del servicio.
     * @param TrabajadorRepository $trabajadorRepository Repositorio de trabajadores.
     * @param ValidatorInterface   $validator           Validador Symfony para validación de entidades.
     */
    public function __construct(TrabajadorRepository $trabajadorRepository,
    ValidatorInterface $validator) {
        $this->trabajadorRepository = $trabajadorRepository;
        $this->validator = $validator;
    
    }


    /**
     * Crea un nuevo trabajador a partir de los datos proporcionados.     
     * @param array $data Datos del trabajador: nombre.     
     * @throws \InvalidArgumentException Si el trabajador ya existe.
     */
    public function crearTrabajador(array $data):void
    {    
        $nombre = $data['nombre'];

        //verifico si el trabajador ya existe
        $nombreExistente = $this->trabajadorRepository->trabajadorExiste($nombre);

        if($nombreExistente){
            //el trabajador ya existe, envío excepcion
            throw new \InvalidArgumentException('ya existe un trabajador con ese nombre');
        }

        //trabajador no existe
        $trabajador = new Trabajador();

        $trabajador->setNombre($nombre);
                
        //Validacion de la entidad
        $errors = $this->validator->validate($trabajador);

        if(count($errors)>0){
            $errorMessages = [];
            foreach($errors as $error){
                $errorMessages[] = $error->getMessage();

            }
            throw new \InvalidArgumentException(implode(', ', $errorMessages));
        
        }

        $this->trabajadorRepository->add($trabajador,true); 
                 
    } 
    
    /**
     * Lista todos los trabajadores disponibles.
     * @return array Lista de trabajadores en forma de arrays.
     */
    public function listarTrabajadores(): array
    {
        $trabajadores = $this->trabajadorRepository->findAll();
        $trabajadoresArray = [];
    
        foreach ($trabajadores as $trabajador) {
            $trabajadoresArray[] = [
                'id' => $trabajador->getId(),
                'nombre' => $trabajador->getNombre(),              
            ];
        }
    
        return $trabajadoresArray;
    }

}