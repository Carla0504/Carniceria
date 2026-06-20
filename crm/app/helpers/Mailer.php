<?php
require_once __DIR__ . '/phpmailer/Exception.php';
require_once __DIR__ . '/phpmailer/PHPMailer.php';
require_once __DIR__ . '/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    public static function enviar($destinatario, $asunto, $cuerpoHtml) {
        $cfg = require __DIR__ . '/../../config/mail.php';

        // si no hay credenciales configuradas no intento enviar
        if (empty($cfg['user']) || empty($cfg['pass'])) {
            error_log("Mailer: sin credenciales. Asunto: $asunto -> $destinatario");
            return false;
        }

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $cfg['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $cfg['user'];
            $mail->Password = $cfg['pass'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $cfg['port'];
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($cfg['user'], $cfg['from_name']);
            $mail->addAddress($destinatario);
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $cuerpoHtml;
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mailer error: ' . $e->getMessage());
            return false;
        }
    }
}
