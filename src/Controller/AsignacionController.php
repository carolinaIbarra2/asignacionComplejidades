<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\AsignacionService;


/**
 * Controlador para gestionar operaciones relacionadas con asignaciones.
 *
 */
class AsignacionController extends AbstractController
{
    private $asignacionService;

    /**
     * Constructor del controlador.
     * @param AsignacionService $asignacionService Servicio de asignaciones.
     */
    public function __construct(AsignacionService $asignacionService) {
        $this->asignacionService = $asignacionService;
    }

    
    /**
     * Renderiza la página principal de asignaciones.
     * @Route("/asignacion", name="app_asignacion")
     */
    public function index(): Response
    {
        return $this->render('asignacion/index.html.twig', [
            'controller_name' => 'AsignacionController',
        ]);
    }


    /**
     * Almacena una asignacion en la base de datos.     
     * @param Request $request Contiene los datos enviados por el cliente: fecha_asignacion, id_soporte
     * @return JsonResponse Mensaje que indica el estado de la función.
     * @Route("/asignacion/crear", name="app_asignacion_crear", methods={"POST"})
     */
    public function crearAsignacion(Request $request): JsonResponse
    {
        try{            
            $data = json_decode($request->getContent(), true);
            $trabajador = $this->asignacionService->crearAsignacion($data);
            return $this->json('Asignacion guardada  '.$trabajador->getNombre());         

        } catch (\InvalidArgumentException $ex) {
            return $this->json($ex->getMessage(), 400); 
        } catch (\Exception $ex) {
            return $this->json('Servidor caído', 500);
        }
    }

    /**
     * Lista todas las asignaciones por dia del trabajador disponibles
     * @return JsonResponse Devuelve un JSON con la lista de trabajadores
     * @Route("/asignacion/listar", name="listar_asignaciones", methods={"GET"})
     */
    public function listarAsignacionesDia(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $respuesta = $this->asignacionService->listarTrabajadorConCargaAcumulada($data);

            return new JsonResponse($respuesta);
        }catch (\Exception $ex) {
            return $this->json('Servidor caído', 500);
        }
       
        
    }

}
