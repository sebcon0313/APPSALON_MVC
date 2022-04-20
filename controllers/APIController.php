<?php 

namespace Controllers;

use Models\Cita;
use Models\citaServicio;
use Models\Servicio;

class APIController {

    public static function index() {
        
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar(){
        
        // Almacena la Cita y devulve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        // Almacena los servicios con el id de la cita
        $idServicios = explode(',',$_POST['servicios']);
        foreach ($idServicios as $idServicio) {
            $args = [
                'citasId' => $id,
                'serviciosId' => $idServicio
            ];
            $citaServicio = new citaServicio($args);
            $citaServicio->guardar();
        }
        // Retornar respuesta
        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar(){
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id']; 

            $cita = Cita::find($id);
            $cita->eliminar();

            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    } 

}