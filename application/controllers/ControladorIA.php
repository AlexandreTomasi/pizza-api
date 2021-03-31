<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorIA
 *
 * @author Alexandre
 */
class ControladorIA extends CI_Controller{
    //put your code here
    
    public function perguntas(){
        $this->load->model("modelos/ControladorFluxo");
        try{
            if(!isset($_GET["Codg_pizzaria"])){throw new Exception("Codigo da Pizzaria não existente");}
            if(!isset($_GET["last_visited_block_name"])){throw new Exception("last_visited_block_name não existente");}
            if(!isset($_GET["fluxo"])){throw new Exception("fluxo não existente");}
            if(!isset($_GET["first_name"])){throw new Exception("fb_first_name não existente");}
            if(!isset($_GET["last_name"])){throw new Exception("fb_last_name não existente");}
            if(!isset($_GET["last_user_freeform_input"])){throw new Exception("last_user_freeform_input não existente");}

            $pizzaria = $_GET["Codg_pizzaria"];
            $blocoAtual = $_GET["last_visited_block_name"];
            $fluxo = $_GET["fluxo"];
            $nome = $_GET["first_name"];
            $sobrenome = $_GET["last_name"];
            $mensagem = $_GET["last_user_freeform_input"];

            $permite = $this->ControladorFluxo->validaFluxoAtual($blocoAtual, $fluxo);
            if($permite == 0){
                echo $this->ControladorFluxo->validaBloco($blocoAtual, $fluxo);
            }else{// se permite estou em welcome, começar, Finalizando Pedido, Cancela Pedido, Finalizando Pedido UP, Default Answer
                $this->load->model("ia/AnalisadorPergunta");
                $resposta = $this->AnalisadorPergunta->analisador($mensagem, 1, $pizzaria);// 1 gerente alexandre
                if(strnatcasecmp($resposta,"null") == 0){
                    $mensagem = "";
                }else{
                    $mensagem = $resposta;
                }
                if($resposta == null || $resposta == "" ){
                    $mensagem = "Desculpa, ".$nome.". Eu ainda estou aprendendo a me comunicar, favor falar comigo somente pelos botões.";
                }
                
                
                /*
                if( strnatcasecmp("Ok", $mensagem)==0 || strnatcasecmp($mensagem,"ok")==0 || strnatcasecmp($mensagem,"blz")==0 || strnatcasecmp($mensagem,"certo")==0 || strnatcasecmp($mensagem,"beleza")==0
                        || strnatcasecmp($mensagem,"rapido")==0 || strnatcasecmp($mensagem,"esperando")==0 || strnatcasecmp($mensagem,"Ok Obrigado")==0 || strnatcasecmp($mensagem,"Obrigado")==0
                        || strnatcasecmp($mensagem,"Obrigada")==0){
                    $resposta="";
                }
                */
                $dados = array('messages' => array(
                                array(
                                    "attachment" => array(
                                        "type" => "template",
                                        "payload" => array(
                                            "template_type" => "button",
                                            'text' => $mensagem,
                                            "buttons"=>array(
                                                array(
                                                    "type"=> "show_block",
                                                    "block_name"=> "começar",
                                                    "title"=> "Fale comigo"
                                                )
                                            )

                                        ) 
                                    )
                                )
                            )
                        );
                 $json_str = json_encode($dados);
                 echo $json_str;
            }
            
        }catch (Exception $e){
            $fp = fopen("log.txt", "a");
            $escreve = fwrite($fp, "\n".date('Y-m-d H:i:s')." - ".((isset($_GET["Codg_pizzaria"])) ? "Codigo da pizzaria = ".$_GET["Codg_pizzaria"] : "")." ".$e->getMessage());
            fclose($fp); 
        }
    }
}
