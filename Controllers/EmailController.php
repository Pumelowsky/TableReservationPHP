<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

class EmailController
{
    private $mailer;

    //Kontruktor
    public function __construct()
    {
        $config = require '../config/smtp.php';
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = $config['host'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $config['username'];
        $this->mailer->Password = $config['password'];
        $this->mailer->SMTPSecure = 'ssl';
        $this->mailer->Port = $config['port'];
        $this->mailer->setFrom($config['from_email'], $config['from_name']);
    }

    //Funkcja do wysyłania maila
    public function send_email($to, $subject, $message)
    {
        try {
            $this->mailer->addAddress($to);
            $this->mailer->CharSet = "UTF-8";
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $message;
            $this->mailer->send();
            echo "Wiadomość została wysłana pomyślnie!";
        } catch (Exception $e) {
            echo "Błąd podczas wysyłania wiadomości: {$this->mailer->ErrorInfo}";
        }
    }

}