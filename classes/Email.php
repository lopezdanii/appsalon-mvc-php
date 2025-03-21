<?php

namespace Classes;


use PHPMailer\PHPMailer\PHPMailer;

class Email{
    public $email;
    public $nombre;
    public $token;


    public function __construct($email, $nombre, $token){
        $this->email= $email;
        $this->nombre= $nombre;
        $this->token= $token;
    }

    public function enviarConfirmacion(){
        //crear objeto de email
        $mail = new PHPMailer();

        //Server settings
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail = new PHPMailer();
        $mail->isSMTP();                                //Send using SMTP
        $mail->Host = $_ENV['EMAIL_HOST'];       //Set the SMTP server to send through
        $mail->SMTPAuth = true;                         //Enable SMTP authentication  
        $mail->Port = $_ENV['EMAIL_PORT'];                             //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];    
            

        $mail->setFrom('cuentas@appsalon.com'); //Dominio que se use posteriormente´
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com'); //Dominio que se use posteriormente´
        $mail->Subject='Confirma tu cuenta';

        //Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet='UTF-8';


        $contenido="<html>";
        $contenido.="<p><strong>Hola " . $this->nombre . "</strong></p>";
        $contenido.="<p>Has creado tu cuenta en App Salon,
         para confirmarla debes presionar el siguiente enlace </p>";
        $contenido.="<p>Presiona aquí: <a href='" . $_ENV['APP_URL']. "/confirmar-cuenta?token=" . $this->token ."'>Confirmar cuenta </a> </p>";

        $contenido.= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";

        $contenido.="</html>";

        $mail->Body= $contenido;

        //Enviar email
        $mail->send();

    }

    //Enviar email con nueva contraseña 
    public function enviarInstrucciones(){

        //crear objeto de email
        $mail = new PHPMailer();

        //Server settings
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail = new PHPMailer();
        $mail->isSMTP();                                //Send using SMTP
        $mail->Host = $_ENV['EMAIL_HOST'];       //Set the SMTP server to send through
        $mail->SMTPAuth = true;                         //Enable SMTP authentication  
        $mail->Port = $_ENV['EMAIL_PORT'];                             //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];    
            

        $mail->setFrom('cuentas@appsalon.com'); //Dominio que se use posteriormente´
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com'); //Dominio que se use posteriormente´
        $mail->Subject='Reestablece tu contraseña';

        //Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet='UTF-8';


        $contenido="<html>";
        $contenido.="<p><strong>Hola " . $this->nombre . "</strong></p>";
        $contenido.="<p>Has solicitado restablecer tu contraseña, sigue el siguiente enlace para hacerlo.
         </p>";
        $contenido.="<p>Presiona aquí: <a href='" . $_ENV['APP_URL']. "/recuperar-password?token=" . $this->token ."'>Restablecer contraseña  </a> </p>";

        $contenido.= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";

        $contenido.="</html>";

        $mail->Body= $contenido;

        //Enviar email
        $mail->send();

            }


}