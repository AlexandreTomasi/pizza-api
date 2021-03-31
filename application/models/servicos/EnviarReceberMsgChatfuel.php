<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ManterGerencia
 *
 * @author Alexandre
 */
class EnviarReceberMsgChatfuel extends CI_Model{
    //put your code here
    
    public function enviarMensagemAoChatbotViaSite(){
        $this->load->model("servicos/EnviarEmail");
        try{
            // enviar a msg para o chatfuel
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".'Inicio Via Site foi enviado para o CHATFUEL uma msg');
                 
            $user_id = '1434653713283061';// id do alexandre ferreira
            $token = 'vnbqX6cpvXUXFcOKr5RHJ7psSpHDRzO1hXBY8dkvn50ZkZyWML3YdtoCnKH7FSjC';//skybots pizzaria
            $bot_id ='5977d6a7e4b06bc39f14aff7';
            $block_id='5997030ae4b0255488feaeab';
            $block_name='Servico_ativo';
            $user_atrribute="&servicoMensagem=".date('Y-m-d/H:i:s');
            
            $url ="https://api.chatfuel.com/bots/";
            $url=$url.$bot_id."/";
            $url=$url."users/".$user_id."/send?chatfuel_token=";
            $url=$url.$token;
            $url=$url."&chatfuel_block_name=".$block_name;
            $url=$url.$user_atrribute;

            $post = curl_init();
            curl_setopt($post, CURLOPT_URL, $url);
            curl_setopt($post, CURLOPT_POST, true);
            curl_setopt($post, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($post);
            $resp = json_decode($result);
            $resposta = "";
            if($resp != null && $resp->{'success'} == true){
                $resposta = "A mensagem foi enviada com sucesso";
            }else{
                $resposta = "Não foi possivel enviar mensagem para o chatfuel.";
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - "."Via Site ".$resposta);
                $resp = $this->EnviarEmail->enviarEmailmessage($resposta.", verificar. Mensagem: ".date('Y-m-d/H:i:s'));
                if($resp == false){
                    throw new Exception("Erro ao enviar email.");
                }
                throw new Exception($resposta);
            }
            curl_close($post);
            fclose($fp);
            return $resposta;
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".'gerado apartir do serviço '.$e->getMessage());
                fclose($fp); 
        }  
    }
    
    public function servicoVerificaMensagemRecebidaViaSite(){
        $this->load->model("servicos/EnviarEmail");
        try{
            $fp = fopen("log_skybots.txt", "a");
            // ler o arquivo se gravou a msg
            $ponteiro = fopen ("log_servico_chatfuel.txt","r");
            $status = false;
            while ((!feof ($ponteiro))) {
                $linha = fgets($ponteiro,4096);
                if(strlen($linha) > 18){
                    $temp = explode("/",$linha);
                    $dia = explode("-",$temp[0]);
                    $hora = explode(":",$temp[1]);
                    if($dia[0] == date("Y") && $dia[1] == date("m") && $dia[2] == date("d") && $hora[0] == date("H")
                            && (date("i") - $hora[1]) < 15){
                        $status = true;
                        break;
                    }
                }
            }
            fclose($ponteiro); 
            $ativo = "";
            if($status == false){// quer dizer que nao teve resposta
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - "."Fim Via Site NÃO foi recebida a msg. Constatado que o chatbot NAO está ativo.\n");
                $resp = $this->EnviarEmail->enviarEmailmessage("O chatfuel não respondeu verificar".date('Y-m-d/H:i:s'));
                if($resp == false){
                    throw new Exception("Erro ao enviar email.");
                }
                $ativo= "SEM RESPOSTA";
            }else{
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - "."Fim Via Site foi recebida a msg. Constatado que esta ATIVO\n");
                $ativo= "ATIVO";
            }
            fclose($fp); 
            return "Foi verificado se a mensagem foi recebida com sucesso. Resultado: ".$ativo; 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".'gerado apartir do serviço '.$e->getMessage());
            fclose($fp); 
        }  
    }
    
    public function enviarMensagemAoChatbotViaServico(){
        $this->load->model("servicos/EnviarEmail");
        try{
            // enviar a msg para o chatfuel
            $fp = fopen("log_skybots.txt", "a");
            //$escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".'Servico de verificacao de envio e recebimento de mensagem para o CHATFUEL foi inciado!');
                 
            $user_id = '1434653713283061';// id do alexandre ferreira
            $token = 'BxXmPaeffbIPttnrUQ00m6jjbqHRpi4Mo2jhqeAnswxKz17iKEpKHxd4hqTbHCSB';//veterana pizzaria
            $bot_id ='58a26cbee4b0bd0cc832db73';
            $block_id='59c6fa19e4b0b12ddabcdde2';
            $block_name='Servico_ativo';
            $user_atrribute="&servicoMensagem=".date('Y-m-d/H:i:s');
            
            $url ="https://api.chatfuel.com/bots/";
            $url=$url.$bot_id."/";
            $url=$url."users/".$user_id."/send?chatfuel_token=";
            $url=$url.$token;
            $url=$url."&chatfuel_block_name=".$block_name;
            $url=$url.$user_atrribute;

            $post = curl_init();
            curl_setopt($post, CURLOPT_URL, $url);
            curl_setopt($post, CURLOPT_POST, true);
            curl_setopt($post, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($post);
            $resp = json_decode($result);
            $resposta = "";
            if(!($resp != null && $resp->{'success'} == true)){
                $resposta = "Não foi possivel enviar mensagem para o chatfuel.";
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - "."Servico (verifica Atividade Chatbot) ".$resposta);
                $resp = $this->EnviarEmail->enviarEmailmessage($resposta.", verificar. Mensagem: ".date('Y-m-d/H:i:s'));
                if($resp == false){
                    throw new Exception("Erro ao enviar email.");
                }
                throw new Exception($resposta);
            }
            curl_close($post);
            fclose($fp);
        }catch (Exception $e){
                $fp = fopen("log_skybots.txt", "a");
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".'gerado apartir do serviço '.$e->getMessage());
                fclose($fp); 
        }  
    }
    public function servicoVerificaMensagemRecebidaViaServico(){
        $this->load->model("servicos/EnviarEmail");
        try{
            $fp = fopen("log_skybots.txt", "a");
            // ler o arquivo se gravou a msg
            $ponteiro = fopen ("log_servico_chatfuel.txt","r");
            $status = false;
            while ((!feof ($ponteiro))) {
                $linha = fgets($ponteiro,4096);
                if(strlen($linha) > 18){
                    $temp = explode("/",$linha);
                    $dia = explode("-",$temp[0]);
                    $hora = explode(":",$temp[1]);
                    if($dia[0] == date("Y") && $dia[1] == date("m") && $dia[2] == date("d") && $hora[0] == date("H")
                            && (date("i") - $hora[1]) < 5){
                        $status = true;
                        break;
                    }
                }
            }
            fclose($ponteiro); 
            if($status == false){// quer dizer que nao teve resposta
                $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - "."Servico (verificaAtividadeChatbot) constatou que o chatbot NAO está ativo.");
                $resp = $this->EnviarEmail->enviarEmailmessage("O chatfuel não respondeu verificar".date('Y-m-d/H:i:s'));
                if($resp == false){
                    throw new Exception("Erro ao enviar email.");
                }
            }else{
                //$escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - "."Servico constatou que esta ATIVO");
            }
            //$escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - "."Servico de verificacao de envio e recebimento de mensagem para o CHATFUEL foi finalizado!");
            fclose($fp); 
        }catch (Exception $e){
            $fp = fopen("log_skybots.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".'gerado apartir do serviço '.$e->getMessage());
            fclose($fp); 
        }  
    }
}
