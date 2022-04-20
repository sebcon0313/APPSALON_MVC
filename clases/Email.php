<?php

namespace Clases;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {


        // Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'f31fe309fdecb7';
        $mail->Password = 'bbe4be54bfca32';

        $mail->setFrom('cuentas@appsalon.com', 'App Salon');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Confirma tu cuenta';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en appAsalon, solo debes confirmar que eres tu en el siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui: <a href='http://localhost:8000/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .=   "</html>";
        $mail->Body = $contenido;

        // Enviar el email 
        $mail->send();
    }

    public function enviarInstrucciones(){
        // Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'f31fe309fdecb7';
        $mail->Password = 'bbe4be54bfca32';

        $mail->setFrom('cuentas@appsalon.com', 'App Salon');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Restablece tu Contraseña';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado restablecer tu contraseña</p>";
        $contenido .= "<p>Presiona aqui: <a href='http://localhost:8000/recuperar?token=" . $this->token . "'>Restablecer Contraseña</a></p>";
        $contenido .= "<p>Si tu no lo solicitaste, puedes ignorar el mensaje</p>";
        $contenido .=   "</html>";
        $mail->Body = $contenido;

        // Enviar el email 
        $mail->send();
    }
}
