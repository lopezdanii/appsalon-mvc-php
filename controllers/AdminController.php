<?php
namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController {

    public static function index(Router $router){
        session_start();
        isAdmin();

        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        //Comprobar fecha
        $fechas=  explode('-', $fecha);
        $checkDate= checkdate($fechas[1],$fechas[2],$fechas[0]);

        if( !$checkDate){
            header('Location: /404');
        }
        //Consultar BBDD
        $consulta = "SELECT c.id, c.hora, CONCAT(u.nombre,' ', u.apellido) as cliente, u.email, u.telefono, s.nombre as servicio, s.precio ";
        $consulta.= " FROM citas c ";
        $consulta.= " LEFT JOIN usuarios u ON c.usuarioId=u.id ";
        $consulta.= " LEFT JOIN citasservicios cs ON c.id=cs.citaId ";
        $consulta.= " LEFT JOIN servicios s ON cs.servicioId=s.id ";
        $consulta.= " WHERE fecha = '$fecha' ";

        $citas= AdminCita::SQL($consulta);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}