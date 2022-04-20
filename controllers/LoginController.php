<?php

namespace Controllers;

use Clases\Email;
use Models\Usuario;
use MVC\Router;

class LoginController {

    public static function login(Router $router){
        $alertas = [];
        $auth = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                // validar el email
                $usuario = $auth->where('email', $auth->email);

                if ($usuario) {
                    // validar la contraseña
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        //Autenticar al Usuario
                        
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento 
                        if ($usuario->admin === '1') {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else{
                            header('Location: /cita');
                        }
                    }
                }else{
                    //mostar alerta de email
                    Usuario::setAlerta('error', 'El email que ingreso no esta registrado');
                }

            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login',[
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    public static function logout(){
        session_start();
        
        $_SESSION = [];
        
        header('Location: /');
        
    }

    public static function olvide(Router $router){
        
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                // Verificar que el email exista
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario && $usuario->confirmado === '1') {
                    // Generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    //  enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Hemos enviado las intrucciones a tu Correo');
                    //debuguear($usuario);
                }else{
                    Usuario::setAlerta('error', 'usuario no encontrado o no esta registrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){
        $alertas =[];
        $error = false;

        $token = s($_GET['token']) ?? null;

        // revisarl que el token exista
        $usuario = Usuario::where('token', $token);

        if (!$usuario) {
            // alerta de error
            Usuario::setAlerta('error', 'token no valido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $password = new Usuario($_POST);

            if ($_POST['password'] !== $_POST['password2']) {
                Usuario::setAlerta('error', 'Los passwords no coinciden');
            }else{
                $alertas = $password->validarPassword();
            }

            if (empty($alertas)) {
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();

                if ($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password',[
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
        $usuario = new Usuario;

        //alertas vacias
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //revisar que las alertas esten vacias
            if (empty($alertas)) {
                $resultado = $usuario->existeUsuario();  

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                }else {
                    //hashear el password
                    $usuario->hashPassword();

                    // Generar un token unico
                    $usuario->creartoken();

                    // Enviar el Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();

                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }
        
        $router->render('auth/crear-cuenta',[
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){

        $router->render('auth/mensaje');

    }

    public static function confirmar(Router $router){

        $alertas = [];
        $token = s($_GET['token']);
        
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no valido');
        }else {
            //modificar a usuarigo confirmado
            $usuario->confirmado = '1';
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }
        // Obtener Alertas
        $alertas = Usuario::getAlertas();
        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }

}