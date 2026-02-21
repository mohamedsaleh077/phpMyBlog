<?php

namespace Controllers;
use RobThree\Auth\Providers\Qr\QRServerProvider;
use RobThree\Auth\TwoFactorAuth;

class Tfa
{
    private $tfa;
    public function __construct(){
        $this->tfa = new TwoFactorAuth(new QRServerProvider(), "phpMyBlog");
        if(!isset($_SESSION['2FA'])){
            $_SESSION['2FA'] = $this->tfa->createSecret();
        }
    }

    public function verifyCode($userSecret, $code){
        return $this->tfa->verifyCode($userSecret, $code);
    }

    public function QRCode(){
        header('Content-type: image/png');
        $im = imagecreatefrompng($this->tfa->getQRCodeImageAsDataUri('phpMyAdmin', $_SESSION['2FA']));
        imagepng($im);
    }
}


