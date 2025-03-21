<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController{
    public function __construct(){

    }

    public static function login(Router $router){
        $alertas =[];

        $auth= new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth-> sincronizar($_POST);

            //Validar el email y la contraseña
            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //Comprobar que exista el usuario
                $usuario=Usuario::where('email',$auth->email);

                if($usuario){
                    //Verificar password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){

                        //Autenticar usuario
                        session_start();

                        $_SESSION['id']= $usuario->id;
                        $_SESSION['nombre']= $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email']= $usuario->email;
                        $_SESSION['login']= true;


                        //Redireccionamiento
                        if($usuario->admin == 1){
                            $_SESSION['admin']=$usuario->admin;
                            header('Location: /admin');
                        }else{
                            header('Location: /cita');
                        }
                    }

                }else{
                    Usuario::setAlerta('error','Usuario no registrado');
                }
            }
        }

        $alertas= Usuario::getAlertas();

        //Pasamos la info a la vista
        $router -> render('/auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);

    }
    //Cerrar sesión
    public static function logout(){
        session_start();

        $_SESSION = [];

        header('Location: /');
    }
    public static function passwordOlvidada(Router $router){
        $alertas=[];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas= $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);

                if($usuario){
                    if($usuario->confirmado== 1){
                        //generar token temporal
                        $usuario->crearToken();

                        $usuario->guardar();


                        //Enviar Email
                        $email= new Email($usuario->email,$usuario->nombre,$usuario->token);
                        $email->enviarInstrucciones();

                        //Alerta de exito
                        Usuario::setAlerta('exito','Instrucciones enviadas. Revisa tu email');

                    }else{
                        Usuario::setAlerta('error', 'El usuario no está confirmado');
                    }
                }
                else{
                    Usuario::setAlerta('error', 'El email no pertenece a ningun usuario');
                }

            }
        }

        $alertas=Usuario::getAlertas();

        //Pasamos la info a la vista
        $router -> render('/auth/password-olvidada', [
            'alertas' => $alertas
        ]);
        
    }

    public static function recuperarPassword(Router $router){
        $alertas=[];
        $error=false;

        //Se obtiene el token de la URL a traves de GET
        $token= s($_GET['token']);
        //Busqueda de registro con el token generado
        $usuario= Usuario::where('token',$token);

        //Se actualiza el usuario como confirmado, y se borra el token
        if(empty($usuario)){
            //Mostrar mensaje error
            Usuario::setAlerta('error', "Token no válido");
            $error=true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $password= new Usuario($_POST);

            $alertas=$password->validarPassword();

            if(!$alertas){
                //Se limpia contraseña previa, se asigna nueva contraseña y se hashea
                $usuario->password=null;
                $usuario->password= $password->password;
                $usuario->hashPassword();

                $usuario->token=null;

                //Se actualiza el registro en BBDD
                $resultado=$usuario->guardar();
                if($resultado){
                    header('Location: /');
                }

            }
        }


        $alertas= Usuario::getAlertas();

        //Pasamos la info a la vista
        $router -> render('/auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error

        ]);
    }
    public static function crear(Router $router){
        $usuario= new Usuario();
        $alertas=[];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Se recuperan los valores de POST y se informa el objeto usuario
            $usuario->sincronizar($_POST);
            $alertas=$usuario->validarNuevaCuenta();

            //Revisar que alertas este vacio
            if(empty($alertas)){
                //Se revisa si el usuario ya existe 
                
                $resultado=$usuario->existeUsuario();
                
                if($resultado->num_rows){
                    $alertas= Usuario::getAlertas();
                } else{
                    //Hashear password
                    $usuario->hashPassword();

                    //Generar token unico
                    $usuario->crearToken();

                    //Enviar email
                    $email= new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();

                    //Guardar en BBDD
                    $resultado=$usuario->guardar();

                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }



        //Se llama a la vista con los datos
        $router -> render('/auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);

    }


    public static function mensaje(Router $router){

        $router->render('/auth/mensaje');
    }
    public static function confirmar(Router $router){

        $alertas=[];
        //Se obtiene el token de la URL a traves de GET
        $token= s($_GET['token']);

        //Busqueda de registro con el token generado
        $usuario= Usuario::where('token',$token);

        //Se actualiza el usuario como confirmado, y se borra el token
        if(empty($usuario)){
            //Mostrar mensaje error
            Usuario::setAlerta('error', "Token no válido");
        }else{
            //Modificar usuario confirmado
            $usuario->confirmado=1;
            $usuario->token=null;

            //Se actualiza el registro en BBDD
            $usuario->guardar();
            $usuario->setAlerta('exito', 'Cuenta comprobada correctamente');

        }

        $alertas= Usuario::getAlertas();

        //LLamar a la vista con los datos del modelo
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}