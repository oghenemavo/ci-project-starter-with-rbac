<?php

use Config\Services;
use Ecosystem\Authentication\Libraries\MailerLib;

if (!function_exists('send_mail')) {

    function send_mail(string $template, array $address, array $data, array $attachment = [])
    {
        return Services::mailerLib()->send_mail($template, $address, $data, $attachment);
    }
}
