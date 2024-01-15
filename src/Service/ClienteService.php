<?php

namespace App\Service;

use App\Entity\Cliente;
use App\Repository\ClienteRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Servicio para gestionar operaciones relacionadas con clientes.
 */
class ClienteService{

    private $clienteRepository;
    private $validator;


    /**
     * Constructor del servicio.
     *
     * @param ClienteRepository    $clienteRepository Repositorio de clientes.
     * @param ValidatorInterface   $validator         Validador Symfony para validación de entidades.
     */
    public function __construct(ClienteRepository $clienteRepository, ValidatorInterface $validator) {
        $this->clienteRepository = $clienteRepository;
        $this->validator = $validator;
    }


    /**
     * Crea un nuevo cliente a partir de los datos proporcionados.
     * @param array $data Datos del cliente: nombre, apellido, correo.
     * @throws \InvalidArgumentException Si el cliente ya existe o si la validación falla.
     */
    public function crearCliente(array $data):void
    {    
        $correo = $data['correo'];

        //verifico si el cliente ya existe
        $clienteExistente = $this->clienteRepository->clienteExiste($correo);

        if($clienteExistente){
            //el cliente ya existe, envío excepcion
            throw new \InvalidArgumentException('ya existe un cliente con ese nombre correo electronico');
        }

        //cliente no existe
        $cliente = new Cliente();       
        $cliente->setNombre($data['nombre']);
        $cliente->setApellido($data['apellido']);
        $cliente->setCorreo($correo);

        $errors = $this->validator->validate($cliente);

        if(count($errors)>0){
            $errorMessages = [];
            foreach($errors as $error){
                $errorMessages[] = $error->getMessage();

            }
            throw new \InvalidArgumentException(implode(', ', $errorMessages));
        
        }

        $this->clienteRepository->add($cliente,true);           
    } 


     /**
     * Lista todos los clientes disponibles.
     * @return array Lista de clientes en forma de arrays.
     */
    public function listarClientes(): array
    {
        $clientes = $this->clienteRepository->findAll();
        $clientesArray = [];
    
        foreach ($clientes as $cliente) {
            $clientesArray[] = [
                'id' => $cliente->getId(),
                'nombre' => $cliente->getNombre(),
                'apellido' => $cliente->getApellido(),
                'correo' => $cliente->getCorreo()            
            ];
        }
    
        return $clientesArray;
    }
    
}