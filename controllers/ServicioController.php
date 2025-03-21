<?php 

namespace Controllers;

use MVC\Router;
use Model\Servicio;


class ServicioController{

    public static function index(Router $router){
        isAdmin();
        $servicios= Servicio::all();
        
        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);

    }
    public static function crear(Router $router){
        isAdmin();
        $servicio = new Servicio;
        $alertas=[];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
            }
        }
        
        //Se envian a la vista los datos del modelo
        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);

    }
    public static function actualizar(Router $router){
        isAdmin();

        //Se comprueba que el id es un numero, para evitar inyecciones
        if(!is_numeric($_GET['id'])) return;
        
        //Se busca el servicio seleccionado por id
        $servicio = Servicio::find($_GET['id']);
        $alertas=[];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Se sincroniza objeto servicio con los datos que introduce el usuario, se validan los campos del formulario y se actualiza en BBDD
            $servicio->sincronizar($_POST);

            $alertas= $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas,
        ]);
    }

    //Funcion eliminar
    public static function eliminar(){
        isAdmin();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id= $_POST['id'];

            $servicio= Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');
        }
    }
}