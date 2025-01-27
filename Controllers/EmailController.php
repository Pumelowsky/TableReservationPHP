<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
class EmailController
{
    private $mailer;

    public function __construct()
    {
        // Wczytanie konfiguracji SMTP
        $config = require '../config/smtp.php';

        $this->mailer = new PHPMailer(true);

        // Konfiguracja SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host = $config['host'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $config['username'];
        $this->mailer->Password = $config['password'];
        $this->mailer->SMTPSecure = $config['encryption'] === 'tls'
            ? PHPMailer::ENCRYPTION_STARTTLS
            : PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port = $config['port'];

        // Ustawienie domyślnego nadawcy
        $this->mailer->setFrom($config['from_email'], $config['from_name']);
    }

    public function send_email($to, $subject, $message)
    {
        try {
            // Dodanie odbiorcy
            $this->mailer->addAddress($to);

            // Treść e-maila
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $message;

            // Wysyłanie wiadomości
            $this->mailer->send();
            echo "Wiadomość została wysłana pomyślnie!";
        } catch (Exception $e) {
            echo "Błąd podczas wysyłania wiadomości: {$this->mailer->ErrorInfo}";
        }
    }
}
