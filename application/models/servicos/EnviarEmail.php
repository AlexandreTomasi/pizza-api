<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EnviarEmail
 *
 * @author 033234581
 */
class EnviarEmail extends CI_Model{
    //put your code here
    public function enviarEmailmessage($msg){
        if($msg == null || $msg == ""){
            return false;
        }
        $message = $msg;
        $from = "sac@skybots.com.br";
        $to = "alexandretomasi18@gmail.com";
        //O assunto do e-mail.
        $subject = "Email enviado do servidor SKYBOTS comunicando nova excessão no log do chat";
        $headers = "De:".$from;
        //retorna true ou false
        return Mail($to, $subject, $message, $headers);
    }
}
