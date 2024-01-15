<?php

namespace App\Controller;


use App\Service\SoporteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;



/**
 * Controlador para gestionar operaciones relacionadas con soportes.
 */
class SoporteController extends AbstractController
{

    private $soporteService;
    
     /**
     * Constructor del controlador.
     * @param SoporteService $soporteService Servicio de soportes.
     */
    public function __construct(SoporteService $soporteService) {
        $this->soporteService = $soporteService;
       
    }

    /**
     * Renderiza la página principal de soportes.
     * @Route("/soporte", name="app_soporte")
     */
    public function index(): Response
    {
        return $this->render('soporte/index.html.twig', [
            'controller_name' => 'SoporteController',
        ]);
    }


       /**
     * Almacena un soporte en la base de datos.     
     * @param Request $request Contiene los datos enviados por el cliente: cliente_id, descripcion, complejidad, fecha
     * id usuario, id libro
     * @return JsonResponse Mensaje que indica el estado de la función.
     * @Route("/soporte/crear", name="app_soporte_crear", methods={"POST"})
     */
    public function crearSoporte(Request $request): JsonResponse
    {

        try{
            $data = json_decode($request->getContent(), true);
            $this->soporteService->crearSoporte($data);   
              
            return $this->json('Soporte creado con exito'); 
        }catch (\Exception  $ex) {
            return $this->json($ex->getMessage(), 400);  
        }catch (\Exception $ex) {
            return $this->json('Servidor caído', 500);
        }
    }

       
      /**
     * Obtiene todos los soportes con su cliente.
     * @return JsonResponse Devuelve un JSON con los soportes
     * @Route("/soporte/listar", name="listar_soporte", methods={"GET"})
     */
    public function ListarSoporte(): JsonResponse
    {
        try{
            // Llama al método del servicio para obtener los soportes 
            $soportes = $this->soporteService->obtenerSoportes();

            // Devuelve la respuesta con los datos obtenidos del servicio
            return new JsonResponse($soportes);
        }catch (\Exception $ex) {
            return $this->json('Servidor caído', 500);
        }
    }
}