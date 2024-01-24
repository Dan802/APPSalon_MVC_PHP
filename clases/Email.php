<?php 
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {   
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        // Crear el objeto de email
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->SMTPAuth = true;

        $phpmailer->Host = $_ENV['EMAIL_HOST'];
        $phpmailer->Port = $_ENV['EMAIL_PORT'];
        $phpmailer->Username = $_ENV['EMAIL_USER'];
        $phpmailer->Password = $_ENV['EMAIL_PASS'];

        // $phpmailer->setFrom('cuentas@appsalon.com');
        $phpmailer->setFrom($_ENV['EMAIL_USER'], 'Cita Perruna');
        // $phpmailer->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $phpmailer->addAddress($this->email, $this->nombre);
        $phpmailer->Subject = 'Confirma tu Cuenta';

        // Set HTML
        $phpmailer->isHTML(TRUE);
        $phpmailer->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>¡Hola! ¿Que tal ". $this->nombre . "?</strong>";
        $contenido .= "<br>Has creado tu cuenta exitosamente en Cita Perruna, para activar tu cuenta solo da clic en el enlace, ¡Es gratis!.</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['PROJECT_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a>";
        $contenido .= "<p>Si no has solicitado la creación de esta cuenta, puedes ignorar el mensaje.</p>";
        $contenido .= "</html>";
        
        $phpmailer->Body = $contenido;

        $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $phpmailer->AltBody = "Ve al siguiente enlace para activar tu cuenta:  " . $_ENV['HOST'] . "/confirmar-cuenta?token=" . $this->token;

        if($phpmailer->send()){
            echo 'Message has been sent';
            return true;
        }else{
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $phpmailer->ErrorInfo;
            return false;
        }
        // $phpmailer->send();    // Enviar el email
    }

    public function enviarInstrucciones() {
        // Crear el objeto de email
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->SMTPAuth = true;

        $phpmailer->Host = $_ENV['EMAIL_HOST'];
        $phpmailer->Port = $_ENV['EMAIL_PORT'];
        $phpmailer->Username = $_ENV['EMAIL_USER'];
        $phpmailer->Password = $_ENV['EMAIL_PASS'];

        $phpmailer->setFrom($_ENV['EMAIL_USER'], 'Cita Perruna');
        $phpmailer->addAddress($this->email, $this->nombre);
        $phpmailer->Subject = 'Reestablecer tu Contraseña';

        // Set HTML
        $phpmailer->isHTML(TRUE);
        $phpmailer->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>¡Hola! ¿Que tal ". $this->nombre . "?</strong>";
        $contenido .= "<br>Has solicitado reestablecer tu contraseña, sigue el siguiente enlace para hacerlo</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['PROJECT_URL'] . "/recuperar?token=" . $this->token ."'>Reestablecer Contraseña</a>";
        $contenido .= "<p>Si no has solicitado reestablecer tu contraseña, puedes ignorar este mensaje.</p>";
        $contenido .= "</html>";
        
        $phpmailer->Body = $contenido;

        $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $phpmailer->AltBody = "Ve al siguiente enlace para reestablecer tu constraseña:  " . $_ENV['HOST'] . "/recuperar?token=" . $this->token;

        if($phpmailer->send()){
            echo 'Message has been sent';
            return true;
        }else{
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $phpmailer->ErrorInfo;
            return false;
        }
    }
}