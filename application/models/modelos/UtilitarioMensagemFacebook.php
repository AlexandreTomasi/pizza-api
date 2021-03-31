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
// devo usar esses metodos
// 
// redirecionarParaBlocos($mensagem, $atributos, $bloco)
// gerarBotoesRapidos($mensagem, $atributos, $botoes)
// gerarBotaoPadrao($mensagem, $atributos, $botoes)
// gerarGaleria($mensagem, $atributos, $galeria)
// gerarLista($mensagem, $atributos, $lista, $estilo)
// gerarMensagem($mensagem, $atributos)
// definirAtributosDoUsuario($atributos)
class UtilitarioMensagemFacebook extends CI_Model{
    //put your code here
    public function gerarGaleria($mensagem, $atributos, $galeria){
        if($mensagem == null){$mensagem = "";}
        if($atributos != null && $atributos != ""){
            if($galeria != null){
                $dados = array('set_attributes' => $atributos,
                               'messages' => array(
                                    array('text' => $mensagem),
                                    array('attachment' => array(
                                        'type' => 'template',          
                                        'payload' => array(
                                            'template_type' => "generic",
                                            'elements' => $galeria                         
                               )))));
                return $dados;
            }
            return "";
        }else{
           if($galeria != null){
                $dados = array('messages' => array(
                                    array('text' => $mensagem),
                                    array('attachment' => array(
                                        'type' => 'template',          
                                        'payload' => array(
                                            'template_type' => "generic",
                                            'elements' => $galeria                         
                               )))));
                return $dados;
            }
            return "";
        }
    }    
    
    public function gerarMensagem($mensagem, $atributos){
        if($atributos != null && count($atributos) != 0 && $atributos != ""){
            // mensagem com alteração de atributos
            $resposta = array('set_attributes' => $atributos,
            'messages' => array(array('text' => $mensagem))
            );
            return $resposta;
        }else{
            // mensagem pura
            $dados = array('messages' => array(array('text' => $mensagem)));
            return $dados;
        }
    }
    
    public function redirecionarParaBlocos($mensagem, $atributos, $bloco){
        if($mensagem == null){$mensagem = "";}
        if($bloco != null && $bloco != ""){
            if($atributos != null && count($atributos) != 0 && $atributos != ""){
                $resposta= array("messages" =>array(array("text"=>$mensagem)),
                             "set_attributes" => $atributos,
                             "redirect_to_blocks" => array($bloco)
                           );
                return $resposta;
            }else{
                $resposta= array("messages" =>array(array("text"=>$mensagem)),
                             "redirect_to_blocks" => array($bloco)
                           );
                return $resposta;
            } 
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
    
    public function gerarBotoesRapidos($mensagem, $atributos, $botoes){
        if($mensagem == null){$mensagem = "";}
        if($botoes != null && count($botoes) != 0){
            if($atributos != null && count($atributos) != 0 && $atributos != ""){
                $dados = array('set_attributes' => $atributos, 'messages' => array(
                            array(
                                'text' => $mensagem,
                                'quick_replies' => $botoes
                            ))
                          );
                return $dados;
            }else{
                $dados = array('messages' => array(
                                array(
                                    'text' => $mensagem,
                                    'quick_replies' => $botoes
                                )));
                return $dados;
            }
        }
        return "";
    }
    
    public function gerarBotaoPadrao($mensagem, $atributos, $botoes){
        if($mensagem == null){$mensagem = "";}
        if($botoes != null && $botoes != ""){
            if($atributos != null && count($atributos) != 0 && $atributos != ""){
                $dados = array('set_attributes' => $atributos,
                               'messages' => array(array("attachment" => array("type" => "template","payload" => array(
                                    "template_type" => "button",
                                    'text' => $mensagem,
                                    "buttons"=>$botoes
                        )))));
                return $dados;
            }else{
                $dados = array('messages' => array(array("attachment" => array("type" => "template","payload" => array(
                                    "template_type" => "button",
                                    'text' => $mensagem,
                                    "buttons"=>$botoes
                        )))));
                return $dados;
            }
        }else{
            return "";
        }
    }
    
    // capacidade maxima 4 elementos $estilo == large ou compact
   /*   com url
        $galeria[$i] = array(
                "title"=> $i,"image_url"=> "https://portaldeplanos.com.br/wp-content/uploads/2016/08/como-cancelar-o-pacote-de-internet-da-oi.jpg",
                "subtitle"=> $i,"buttons" =>array(array("type"=> "web_url","url"=> "https://www.uol.com.br","title"=> $i))); 
        com botao
        $galeria[$i] = array("title"=> $i,"image_url"=> "","subtitle"=> $i,"buttons" =>array(array("type"=> "show_block","block_name"=> "teste2","title"=> $i)));
        */
    public function gerarLista($mensagem, $atributos, $lista, $estilo){
        if(count($lista) > 4){return "";}
        if($mensagem == null){$mensagem = "";}
        if($estilo == null || $estilo == "" || $estilo != "large"){$estilo = "compact";}
        if($lista != null && $lista != ""){
            if($atributos != null && count($atributos) != 0 && $atributos != ""){
                $dados = array('set_attributes' => $atributos,
                               'messages' => array(
                                    array('text' => $mensagem),
                                    array('attachment' => array(
                                        'type' => 'template',          
                                        'payload' => array(
                                            'template_type' => "list",
                                            "top_element_style" => $estilo,
                                            'elements' => $lista                         
                               )))));
                return $dados;
            }else{
                $dados = array('messages' => array(
                                    array('text' => $mensagem),
                                    array('attachment' => array(
                                        'type' => 'template',          
                                        'payload' => array(
                                            'template_type' => "list",
                                            "top_element_style" =>"large",
                                            'elements' => $lista                         
                               )))));
                return $dados;
            }
        }
    }
    //Messenger supports JPG, PNG and GIF 
    public function enviarImagen($url){
        $dados = array('messages' => array(array('attachment' => array('type' => 'image','payload' => array("url" => $url)))));
        return $dados;
    }
    /*
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
    
    public function especial($mensagem, $atributos, $botoes){
        if($mensagem == null){$mensagem = "";}
        if($botoes != null && $botoes != ""){
            if($atributos != null && count($atributos) != 0 && $atributos != ""){
                $dados = array('set_attributes' => $atributos,
                               'messages' => array(array("attachment" => array("type" => "template","payload" => array(
                                    "template_type" => "button",
                                    'text' => $mensagem,
                                    "buttons"=>$botoes
                        )))));
                return $dados;
            }else{
                $dados = array('messages' => array(array("attachment" => array("type" => "template","payload" => array(
                                    "template_type" => "button",
                                    'text' => $mensagem,
                                    "buttons"=>$botoes
                        )))));
                return $dados;
            }
        }else{
            return "";
        }
    }*/
}
                    