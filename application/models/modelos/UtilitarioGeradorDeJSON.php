<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UtilitarioGeradorDeJSON
 *
 * @author Alexandre
 */
class UtilitarioGeradorDeJSON extends CI_Model{
    //put your code here
    public function redirecionarParaBlocos($mensagem, $bloco){
        if($bloco != null && $bloco != ""){
            if($mensagem != null && $mensagem != ""){
                $resposta= array("messages" =>array(array("text"=>$mensagem))
                    ,"redirect_to_blocks" => array($bloco));
            }else{
                $resposta = array("redirect_to_blocks" => array($bloco));
            }
            return $resposta;
        }else{
            return "";
        }
    }
    
    public function definirAtributosDoUsuario($atributos){//os atributos tem que ser um array de nome e valor
        if($atributos != null && count($atributos) != 0){
            $resposta = array('set_attributes' => $atributos,
            "block_names"=> "",
            "type"=> "show_block",
            "title"=> "go"
            );
            return $resposta;
        }else{
            return "";
        }
    }
    
    public function gerarMensagemDeTexto($mensagem){
            $dados = array('messages' => array(array('text' => $mensagem)));
            return $dados;
    }
    public function gerarMensagemDeTextoComAtributos($mensagem, $atributos){
            if($atributos != null && count($atributos) != 0){
                $resposta = array('set_attributes' => $atributos,
                'messages' => array(array('text' => $mensagem))
                );
                return $resposta;
            }else{
                return "";
            }
    }
    //gera reposta rapida apenas com botoes e um bloco em cada botão
    public function gerarRespostaRapida($mensagem, $atributos){//os atributos tem que ser um array de titulo e bloco
        if($mensagem == null){$mensagem = "";}
        if($atributos != null && count($atributos) != 0){
            //gerar os botoes
            $botoes = array();
            for($i=0; $i < count($atributos); $i++){
                $botoes[] = array('title' => $atributos[$i]["titulo"],'block_names' => array($atributos[$i]["bloco"]));
            }
            $dados = array('messages' => array(
                            array(
                                'text' => $mensagem,
                                'quick_replies' => $botoes
                            ))
                          );
            return $dados;
        }
        return "";
    }

    public function gerarRespostaRapidaAlterandoAtributos($mensagem, $atributo, $botRapido){//os atributos tem que ser um array de titulo e bloco
        if($mensagem == null){$mensagem = "";}
        if($botRapido != null && count($botRapido) != 0){
            //gerar os botoes
            $botoes = array();
            for($i=0; $i < count($botRapido); $i++){
                $botoes[] = array('title' => $botRapido[$i]["titulo"],'block_names' => array($botRapido[$i]["bloco"]));
            }
            $dados = array('set_attributes' => $atributo, 'messages' => array(
                            array(
                                'text' => $mensagem,
                                'quick_replies' => $botoes
                            ))
                          );
            return $dados;
        }
        return "";
    }
    //gera até 3 botoes que direciona apenas para bloco
    public function gerarBotaoPadrao($mensagem, $atributos){
        if($mensagem == null){$mensagem = "";}
        if($atributos != null && count($atributos) != 0){
            $botoes = array();
            for($i=0; $i < count($atributos); $i++){
                $botoes[] = array('type'=> 'show_block', 'block_name' => $atributos[$i]["bloco"], 'title' => $atributos[$i]["titulo"]);
            }
            $dados = array('messages' => array(array("attachment" => array("type" => "template","payload" => array(
                    "template_type" => "button",'text' => $mensagem,"buttons"=>$botoes)))));
            return $dados;
         }
         return "";
    }
    
    public function gerarGaleria($galeria){
        if($galeria != null){
            $dados = array('messages' => array(
                array('attachment' => array(
                    'type' => 'template',
                    'payload' => array(
                        'template_type' => "generic",
                        'elements' => $galeria                         
                    )                
                ))
             ));
            return $dados;
        }
        return "";
    }
}
