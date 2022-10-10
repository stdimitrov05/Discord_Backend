<?php

namespace App\Lib;


use App\Exceptions\ServiceException;
use App\Services\AbstractService;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer extends AbstractService
{
    private $phpMailer;

    public function __construct($mailer)
    {
        $this->phpMailer = $mailer;
    }

    // Send Activation Key
    public function signUpToken($email, $name, $token)
    {

        //  Use html in letter
        $this->phpMailer->IsHTML(true);

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
        $this->phpMailer->AddAddress($email, $name);

        $this->phpMailer->SetFrom("mstt95607@gmail.com", "M&S_TEAM");

        // Create massage
        $this->phpMailer->Subject = "Account activation";
        $content = "
        <h2 style='text-align: center'>Welcome  " . $name ." !</h2>
        <h3>You can activate your account with this key.</h3>
        <h3>The key is: </h3> <h1>".$token."</h1>
        <footer style='text-align: end'>By " . getenv("USERNAME") ."</footer>
        ";


        $this->phpMailer->msgHTML($content);

//        print_r($this->phpMailer);die;

        if (!$this->phpMailer->send()) {

            throw new ServiceException(
                'Error while sending Email.',
                self::ERROR_CANT_SEND_EMAIL
            );
        }
        return null;

    }

}
