<?php

namespace App\Controller;

use App\Service\TrabajadorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controlador para gestionar operaciones relacionadas con trabajadores.
 *
 */
class TrabajadorController extends AbstractController
{

    private $trabajadorService;

    /**
     * Constructor del controlador.
     * @param TrabajadorService $trabajadorService Servicio de trabajadores.
     */
    public function __construct(TrabajadorService $trabajadorService) {
        $this->trabajadorService = $trabajadorService;
    }

    /**
     * Renderiza la página principal de trabajadores.
     * @Route("/trabajador", name="app_trabajador")
     */
    public function index(): Response
    {
        return $this->render('trabajador/index.html.twig', [
            'controller_name' => 'TrabajadorController',
        ]);
    }


    
    /**
     * Almacena un trabajador en la base de datos.     
     * @param Request $request Contiene los datos enviados por el cliente: nombre
     * @return JsonResponse Mensaje que indica el estado de la función.
     * @Route("/trabajador/crear", name="app_trabajador_crear", methods={"POST"})
     */
    public function crearTrabajador(Request $request): JsonResponse
    {
        try{            
            $data = json_decode($request->getContent(), true);
            $this->trabajadorService->crearTrabajador($data);
            return $this->json('Trabajador guardado');         

        } catch (\InvalidArgumentException $ex) {
            return $this->json($ex->getMessage(), 400);  
        } catch (\Exception $ex) {
            return $this->json('Servidor caído', 500);
        }
    }



      /**
     * Obtiene todos los trabajadores.
     * @return JsonResponse Devuelve un JSON con los trabajadores
     * @Route("/trabajador/listar", name="listar_trabajador", methods={"GET"})
     */
    public function ListarTrabajador(): JsonResponse
    {
        try{
            // Llama al método del servicio para obtener los trabajadores 
            $trabajadores = $this->trabajadorService->listarTrabajadores();

            // Devuelve la respuesta con los datos obtenidos del servicio
            return new JsonResponse($trabajadores);

        }catch (\Exception $ex) {
            return $this->json('Servidor caído', 500);
        }
    }

    
}