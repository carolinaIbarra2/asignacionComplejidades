<?php

namespace App\Controller;

use App\Service\ClienteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Controlador para gestionar operaciones relacionadas con clientes.
 *
 */
class ClienteController extends AbstractController
{

    private $clienteService;

    /**
     * Constructor del controlador.
     * @param ClienteService $clienteService Servicio de clientes.
     */
    public function __construct(ClienteService $clienteService) {
        $this->clienteService = $clienteService;
    }


    /**
     * Renderiza la página principal de clientes.
     * @Route("/cliente", name="app_cliente")
     */
    public function index(): Response
    {
        return $this->render('cliente/index.html.twig', [
            'controller_name' => 'ClienteController',
        ]);
    }


    /**
     * Almacena un cliente en la base de datos.     
     * @param Request $request Contiene los datos enviados por el cliente: nombre, apellido, correo
     * @return JsonResponse Mensaje que indica el estado de la función.
     * @Route("/cliente/crear", name="app_cliente_crear", methods={"POST"})
     */
    public function crearTrabajador(Request $request): JsonResponse
    {
        try{            
            $data = json_decode($request->getContent(), true);
            $this->clienteService->crearCliente($data);
            return $this->json('Cliente guardado');         

        } catch (\InvalidArgumentException $ex) {
            return $this->json($ex->getMessage(), 400);    
        } catch (\Exception $ex) {
            return $this->json('Servidor caído', 500);
        }
    }

    
      /**
     * Obtiene todos los clientes con sus soportes.
     * @return JsonResponse Devuelve un JSON con los clientes
     * @Route("/cliente/listar", name="listar_cliente", methods={"GET"})
     */
    public function ListarCliente(): JsonResponse
    {
        try{
            // Llama al método del servicio para obtener los clientes 
            $clientes = $this->clienteService->listarClientes();

            // Devuelve la respuesta con los datos obtenidos del servicio
            return new JsonResponse($clientes);
        }catch (\Exception $ex) {
            return $this->json('Servidor caído', 500);
        }
    }

}
