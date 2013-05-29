<?php
namespace App\Model\Service;

use App\Model\Registry;

class Mailer extends AbstractService {
    function Send($toEmail, $subject, $body) {
        // old simple mail() func using atm
        $headers  = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: Laf24 <no-reply@laf24.ru>\r\n";
        $headers .= "Reply-To: no-reply@laf24.ru\r\n";

        mail($toEmail, $subject, $body, $headers);
    }

    function SendTemplate($toEmail, $subject, $template, $data = []) {
        /** @var $mailer \App\Model\Data\Protocol\HtmlProtocol */
        $mailer = Registry::Singleton('\App\Model\Data\Protocol\HtmlProtocol');
        $html = $mailer->fetch("{$template}.tpl", $data);
        $this->Send($toEmail, $subject, $html);
    }
}