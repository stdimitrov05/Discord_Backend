<?php

namespace App\Lib;


use App\Exceptions\ServiceException;
use App\Services\AbstractService;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer extends AbstractService
{
    // Config PHPMailer
    private function config()
    {
        $mailer = new PHPMailer();
        $mailer->isSMTP();
        $mailer->SMTPSecure = "tls";
        $mailer->Host = 'smtp.gmail.com';
        $mailer->SMTPAuth = true;
        $mailer->Port = 587;
        $mailer->Username = getenv("GMAIL_EMAIL");
        $mailer->Password = getenv("GMAIL_PASS");

        return $mailer;
    }

    // Send Sing up Token
    public function singUpToken($email, $name, $token)
    {
        $mailer = $this->config();

        //  HTML email
        $mailer->IsHTML(true);


        // Check token and email
        if (!$token) {
            throw  new ServiceException(
                'Token not exist',
                self::ERROR_CONFIRMATION_TOKEN_NOT_EXIST
            );
        }

        if (!$email) {
            throw new ServiceException(
                "Email not exist",
                self::ERROR_EMAIL_NOT_EXIST
            );
        }

        // Add config data
        $mailer->AddAddress($email, $name);
        $mailer->SetFrom("mstt95607@gmail.com", "M&S_TEAM");

        // Create massage
        $mailer->Subject = "Hello " . $name . " !";
        $content = "<h2>Use this token: <h2/>" . $token . " for activated .";

        $mailer->msgHTML($content);


        if (!$mailer->send()) {

            throw new ServiceException(
                'Error while sending Email.',
                self::ERROR_CANT_SEND_EMAIL
            );
        }
        return null;

    }

}